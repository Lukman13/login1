<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use \App\Models\RegisModel;
use \App\Models\TokenModel;

class Home extends BaseController
{
	protected $regisModel;
	protected $tokenModel;
	protected $validation;
	public function __construct()
	{
		$this->validation = \Config\Services::validation();
		$this->tokenModel = new TokenModel();
		$this->regisModel = new RegisModel();
	}
	#menampilkan form login
	public function index()
	{
		if (session()->get('email')) {
			return redirect()->to('/user');
		}
		$data = [
			'title' => 'Login',
			'validation' => $this->validation
		];
		return view('home/login', $data);
	}


	#menampilkan form registrasi
	public function registration()
	{
		if (session()->get('email')) {
			return redirect()->to('/user');
		}
		$data = [
			'title' => 'Registration',
			'validation' => $this->validation
		];
		return view('home/regis', $data);
	}


	#setelah regis, semua data divalidasi disini dan jika lolos validasi data akan disimpan dan activasi akan dikirim lewat email
	public function val()
	{
		if ($this->request->isAJAX()) {
			if (!$this->validate([
				//'nama' => 'required|is_unique[makanan.nama]'
				'name' => [
					'rules' => 'required',
				],
				'email' => [
					'rules' => 'required|trim|valid_email|is_unique[user.email]',
					'errors' => [
						'is_unique' => 'This emailhas already registered'
					]
				],
				'password1' => [
					'rules' => 'required|min_length[3]|matches[password2]',
					'errors' => [
						'matches' => 'Password doesnt match',
						'min_length' => 'password too short'
					]
				],
				'password2' => [
					'rules' => 'required|matches[password1]'
				]
			])) {
				$msg = [
					'error' => [
						'name' => $this->validation->getError('name'),
						'email' => $this->validation->getError('email'),
						'password1' => $this->validation->getError('password1')
					]
				];
			} else {
				$eml = $this->request->getVar('email');
				$token = base64_encode(random_bytes(32));
				$this->tokenModel->save([
					'email' => $eml,
					'token' => $token,
					'date_created' => time()
				]);

				$this->regisModel->save([
					'name' => $this->request->getVar('name'),
					'email' => $eml,
					'image' => 'default.jpg',
					'password' => password_hash($this->request->getVar('password1'), PASSWORD_DEFAULT),
					'role_id' => '2',
					'is_active' => '0'
				]);



				$this->_sendEmail($token, 'verify');

				$msg = [
					'sukses' => [
						'link' => '/home',
						'msg' => "your account has been created, please activate your account"
					]
				];
				// session()->setFlashdata('pesan', 'your account has been created, please activate your account');
				// return redirect()->to('/home');
			}
			echo json_encode($msg);
		}
	}

	#config email untuk aktivasi user, user akan dikirim sebuah link validasi yg berisi token untuk aktivasi, link akan dikirim ke email yg telah di isi dari form regis
	private function _sendEmail($token, $type)
	{
		$email = \Config\Services::email();

		$email->setFrom('lokitech13@gmail.com', 'Lukamnul Hakim');
		$email->setTo($this->request->getVar('email'));
		// $email->setCC('another@another-example.com');
		// $email->setBCC('them@their-example.com');

		if ($type == 'verify') {
			$email->setSubject('Account Verification');
			$email->setMessage('Click this link to reset your password : <a href="' . base_url() . '/home/verify?email=' . $this->request->getVar('email') . '&token=' . urlencode($token) . '">Activate</a>');
		} else if ($type == 'forgot') {
			$email->setSubject('Reset Password');
			$email->setMessage('Click this link to verify your account : <a href="' . base_url() . '/home/resetpassword?email=' . $this->request->getVar('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
		}


		if ($email->send()) {
			return true;
		} else {
			echo $email->printDebugger(['headers']);;
			die;
		}
	}


	#validasi token yang didapat dari aktivasi email, jika token valid dan tidak melewati batas waktu maka user dapat login
	public function verify()
	{
		$email = $this->request->getVar('email');
		$token = $this->request->getVar('token');

		$user = $this->regisModel->getemail($email);

		if ($user) {
			$tkn = $this->tokenModel->token($token);

			if ($tkn) {
				if (time() - $tkn['date_created'] < (60 * 60 * 24)) {
					$this->regisModel->save([
						'id' => $user['id'],
						'is_active' => 1
					]);

					$this->tokenModel->delete($tkn['id']);

					session()->setFlashdata('pesan', '' . $email . ' has been activated! please login');
					return redirect()->to('/');
				} else {
					$this->regisModel->delete($user['id']);
					$this->tokenModel->delete($tkn['id']);
					session()->setFlashdata('danger', 'Account activation failed! token invalid');
					return redirect()->to('/');
				}
			} else {
				session()->setFlashdata('danger', 'Account activation failed! token expied');
				return redirect()->to('/');
			}
		} else {
			session()->setFlashdata('danger', 'Account activation failed! wrong email');
			return redirect()->to('/');
		}
	}

	#form login 
	public function log()
	{
		if ($this->request->isAJAX()) {

			if (!$this->validate([
				'email' => [
					'rules' => 'required|trim|valid_email',
					'errors' => [
						'valid_email' => 'This email doesnt valid'
					]
				],
				'password' => [
					'rules' => 'required|trim'
					// 'errors' => [
					// 	'matches' => 'Password doesnt match',
					// 	'min_length' => 'password too short'
					// ]
				]
			])) {
				$msg = [
					'error' => [
						'email' => $this->validation->getError('email'),
						'password' => $this->validation->getError('password')
					]
				];
			} else {
				return $this->login();
				// return redirect()->to('/home/login')->withInput();
			}
			echo json_encode($msg);
		} else {
			return $this->block();
		}
	}

	#pengecekan email dan password user saat login jika sudah benar dan aktivasi user telah berhasil maka user akan diarahkan ke menu utama(aplikasi)
	public function login()
	{
		$email = $this->request->getVar('email');
		$pw = $this->request->getVar('password');
		$cek = $this->regisModel->getemail($email);
		$nama = $cek['name'];
		if (($cek['email'] == $email)) {
			if (($cek['is_active'] == 1)) {
				if (password_verify($pw, $cek['password'])) {
					$data = [
						'email' => $cek['email'],
						'role_id' => $cek['role_id']
					];
					session()->set($data);
					if ($cek['role_id'] == 1) {
						$msg = [
							'sukses' => [
								'link' => '/admin',
								'msg' => "Selamat datang $nama"
							]
						];
						//return redirect()->to('/admin')->withInput();
					} else {
						$msg = [
							'sukses' => [
								'link' => '/user',
								'msg' => "Selamat datang $nama"
							]
						];
						//return redirect()->to('/user')->withInput();
					}
				} else {
					$msg = [
						'errorlog' => [
							'msg' => 'Password salah',
							'pw' => 'Password salah'
						]
					];
					// session()->setFlashdata('danger', 'Password salah');
					// return redirect()->to('/home/index')->withInput();
				}
			} else {
				$msg = [
					'errorlog' => [
						'msg' => 'Email anda belum teraktivasi',
						'aktivasi' => 'Email anda belum teraktivasi'
					]
				];
				// session()->setFlashdata('danger', 'AAnda belum teraktivasi');
				// return redirect()->to('/home/index')->withInput();
			}
		} else {
			$msg = [
				'errorlog' => [
					'msg' => 'Invalid Email',
					'email' => 'Invalid Email'
				]
			];
			// session()->setFlashdata('danger', 'Gagal Login');
			// return redirect()->to('/home/index')->withInput();
		}
		echo json_encode($msg);
	}


	#logout, menghapus session
	public function logout()
	{
		$array_items = ['email', 'role_id'];
		session()->remove($array_items);

		session()->setFlashdata('pesan', 'logout');
		return redirect()->to('/');
	}

	#jika user mengakses halam yg tidak sesuai rolenya maka akan dialaihkan ke form block
	public function block()
	{
		return view('/home/blocked');
	}


	#opsi forgot password
	public function forgotpassword()
	{
		$data = [
			'title' => 'Forgot Password',
			'validation' => $this->validation
		];
		return view('home/forgot-password', $data);
	}


	#form forgot passwor, akan disuruh menginput email jika email sesuai dengan data user akan dikiri memail link beserta token untuk melakukan ganti password
	public function forgot()
	{
		if ($this->request->isAJAX()) {
			if (!$this->validate([
				'email' => [
					'rules' => 'required|trim|valid_email',
					'errors' => [
						'valid_email' => 'This email doesnt valid'
					]
				]
			])) {
				$msg = [
					'error' => [
						'email' => 'Invalid Email'
					]
				];
			} else {
				$email = $this->request->getVar('email');
				$user = $this->regisModel->get($email);
				if ($user) {;
					$token = base64_encode(random_bytes(32));
					$this->tokenModel->save([
						'email' => $email,
						'token' => $token,
						'date_created' => time()
					]);

					$this->_sendEmail($token, 'forgot');
					$msg = [
						'sukses' => [
							'msg' => 'Please check your email to reset your password'
						]
					];
				} else {
					$msg = [
						'errorlog' => [
							'msg' => 'Email anda belum teraktivasi',
							'aktivasi' => 'Email anda belum teraktivasi'
						]
					];
				}
			}
			return json_encode($msg);
		}
	}

	#jika email dan token sudah benar sesuai data dan waktu token tidak habis maka pasword dapat diganti dan dialihkan ke form ganti password untuk memasukkan passwor baru
	public function resetpassword()
	{
		$email = $this->request->getVar('email');
		$token = $this->request->getVar('token');

		$user = $this->regisModel->getemail($email);

		if ($user) {
			$tkn = $this->tokenModel->token($token);

			if ($tkn) {
				if (time() - $tkn['date_created'] < (60 * 60 * 24)) {

					session()->set('reset_email', $email);

					return $this->changepassword();


					$this->regisModel->save([
						'id' => $user['id'],
						'is_active' => 1
					]);

					$this->tokenModel->delete($tkn['id']);

					session()->setFlashdata('pesan', '' . $email . ' has been activated! please login');
					return redirect()->to('/');
				} else {
					$this->regisModel->delete($user['id']);
					$this->tokenModel->delete($tkn['id']);
					session()->setFlashdata('danger', 'Reset password failed! token expired');
					return redirect()->to('/');
				}
			} else {
				session()->setFlashdata('danger', 'Reset password failed! token invalid');
				return redirect()->to('/');
			}
		} else {
			session()->setFlashdata('danger', 'Reset password failed! Wrong email');
			return redirect()->to('/');
		}
	}


	#form ganti password pada aksi forgot password
	public function changepassword()
	{
		if (!session()->get('reset_email')) {
			return redirect()->to('/home');
		}
		$data = [
			'title' => 'Change Password',
			'validation' => $this->validation
		];
		return view('home/change-password', $data);
	}

	#form validatian untuk forgot password jika benar maka passwor baru akan disimpa dan dapat digunakan
	public function change()
	{
		if (!$this->validate([
			'password1' => [
				'rules' => 'required|min_length[3]|matches[password2]',
				'errors' => [
					'matches' => 'Password doesnt match',
					'min_length' => 'password too short'
				]
			],
			'password2' => [
				'rules' => 'required|min_length[3]|matches[password1]',
				'errors' => [
					'matches' => 'Password doesnt match',
					'min_length' => 'password too short'
				]
			]
		])) {
			return redirect()->to('/home/changepassword')->withInput();;
		} else {
			$pw = password_hash($this->request->getVar('password1'), PASSWORD_DEFAULT);
			$user = $this->regisModel->getemail(session()->get('reset_email'));

			$this->regisModel->save([
				'id' => $user['id'],
				'password' => $pw
			]);

			session()->remove('reset_email');

			session()->setFlashdata('pesan', 'Password has been change! please login');
			return redirect()->to('/home');
		}
	}

	//--------------------------------------------------------------------

}
