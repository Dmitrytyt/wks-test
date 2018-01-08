<?php
	class Controller_users extends Controller{
	
		function __construct()
		{
			if ($_SESSION['role'] != 1) {
				header("Location: /profile");
				exit();
			}
		}
		
		public function search()
		{
			$limit = 10;
			$user_model = Controller::loadModel("user");
			
			$page = 1;
			
			if (isset($_REQUEST['page'])) {
				$page = (int) $_REQUEST['page'];
			}
			
			if (isset($_REQUEST['limit'])) {
				$limit = $_REQUEST['limit'];
			}
			
			if (isset($_REQUEST['field'])) {
				$search_field = $_REQUEST['field'];
			}
			
			if (isset($_REQUEST['text'])) {
				$search_text = $_REQUEST['text'];
			}
			
			$user_model->setFilter($search_field, $search_text);
			
			$pagin = new Paginator($user_model->countUsers(), $limit);
			$pagin->selectPage($page);
			$users = $user_model->selectFilteredUsers($limit, $pagin->getOffset());
			
			$data['error'] = $this->error;
			$data['users'] = $users;
			$data['delete'] = 'users/delete?id=';
			$data['pagin'] = $pagin->getData();
			$data['pages'] = $pagin->getPages();
			$data['search_field'] = $_REQUEST['field'];
			$data['search_text'] = $_REQUEST['text'];
			
			$users_table_view = new View("users_table");
						
			$users_table_view->setData($data);
			
			$users_table_view->display();
		}
		
		public function delete()
		{
			$user_model = Controller::loadModel("user");
			
			$data['result_text'] = "Fail to delete user.";
			
			if (isset($_REQUEST["id"])){
				if ($_REQUEST["id"] == $_SESSION['id']) {
					$data['result_text'] = 'Can\'t to delete himself';
					$result_view = new View("fail");
				} else {
					if ($user_model->deleteUser($_REQUEST["id"])) {
						$result_view = new View("success");
						$data['result_text'] = "User was succesfully deleted.";
					} else {
						$result_view = new View("fail");
					}
				}				
			} else {
				$result_view = new View("fail");
				$data['result_text'] = "Can't delete this user.";
			}
			
			$header_view = new View("header");
			$footer_view = new View("footer");
			
			$header['title'] = "Profile info";
			$data['link_url'] = "/users";
			$data['link_text'] = "View users";
			
			$header_view->setData($header);
			$result_view->setData($data);
			
			$header_view->display();
			$result_view->display();
			$footer_view->display();
		}
		
		public function index()
		{
            if ( $_FILES["file"] ){
                $uploaderModel = Controller::loadModel("upload");
                $result = $uploaderModel->upload();

                if ($result) {
                    $info_view = new View("info");
                    $list_view = new View("upload-list");
                    $info_view->setData(array(
                        'countAddUsers' => $uploaderModel->successAdd,
                        'countNotAddUsers' => $uploaderModel->failAdd
                    ));
                    $list_view->setData(array('users' => $uploaderModel->addedUsers));
                }

            }

			if (isset($_REQUEST['limit'])) {
				$limit = $_REQUEST['limit'];
			}

            if (isset($_REQUEST['limit'])) {
                $limit = $_REQUEST['limit'];
            }
			
			$header['title'] = "Profile info";
			$users['title'] = "View users";
			
			$header_view = new View("header");
			$users_view = new View("users");
            $upload_view = new View("upload-form");
            $random_view = new View("random-user");
			$footer_view = new View("footer");
						
			$header_view->setData($header);
			$users_view->setData($users);
			
			$header_view->display();

            $upload_view->display();
            if ( isset($info_view) ){
                $info_view->display();
                $list_view->display();
            }

            $random_view->display();

            $users_view->display();

			$this->search();
			$footer_view->display();
		}

        public function random()
        {
            $user_model = Controller::loadModel("user");

            $user = $user_model->getRandomUser();

            $data['first_name'] = $user['first_name'];
            $data['last_name'] = $user['last_name'];
            $data['id'] = $user['id'];

            $users_table_view = new View("user-random");

            $users_table_view->setData($data);

            $users_table_view->display();
        }

	}