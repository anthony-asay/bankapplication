<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends CI_Controller {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('client_model');
            $this->load->model('account_model');
            $this->load->helper('url_helper');
        }

        public function authenticate()
        {
            $client = json_decode(file_get_contents('php://input'), true);
            
            if(!$this->client_model->authenticate($client['username'], $client['password']))
            {
                echo 0;
            }
            else
            {
                $id = $this->client_model->authenticate($client['username'], $client['password'])->id;
                $data = array(
                    'client_id' => $id,
                    'accounts' => $this->account_model->getAccountsByClient($id),
                    'info' => $this->client_model->getClient($id)
                    );
                echo json_encode($data);
            }   
        }

        public function getPurchasesByDate()
        {
            $query = json_decode(file_get_contents('php://input'), true);

            if($this->account_model->getPurchasesByDate($query))
            {
                echo json_encode($this->account_model->getPurchasesByDate($query));
            }
            else
            {
                echo 0;
            }
        }

        public function getPurchasesByDay()
        {
            $query = json_decode(file_get_contents('php://input'), true);

            $purchases = $this->account_model->getPurchasesByDay($query);

            if($purchases)
            {
                echo json_encode($purchases);
            }
            else
            {
                echo 0;
            }
        }

        public function getClientsByType($type)
        {
            $data['recipes'] = $this->recipe_model->getClientsByType($type);
            $this->load->view('displayResult', $data);
        }

        public function view($view)
        {
            $this->load->view('Client/'.$view);
        }

        public function viewClient($id)
        {
            $data['client'] = $this->client_model->getClient($id);
            var_dump($data);die;
            $data['accounts'] = $this->account_model->getAccountsByClient($id);
            $this->load->view('Client/header');
            $this->load->view('Client/login', $data);
            $this->load->view('Client/footer');
        }

        public function loadView()
        {
            $this->load->view('Client/header');
        }

        public function getPurchases($id)
        {
            $this->db->select('p.*');
            $this->db->from('purchases p');
            $this->db->where('p.id_account', $account->id);
            $this->db->group_by("p.id");
            $this->db->order_by('p.date','asc');
            $account->purchases = $this->db->get()->result();
        }

        public function addPurchase()
        {
            $purchase = json_decode(file_get_contents('php://input'), true);
            $account = $this->account_model->getAccount($purchase['id_account'])[0];
            $purchase['cur_bal'] = $account->balance + $purchase['amount'];
            $account->balance = $purchase['cur_bal'];
            
            $newId = $this->account_model->setPurchase($purchase);

            if ($newId)
            {
                $purchase['id'] = $newId;
                echo json_encode($purchase);
                $this->account_model->updateAccount($account);
            }
        }

        public function addAccount()
        {
            $account = (object)json_decode(file_get_contents('php://input'), true);
            $this->account_model->setAccount($account);
        }

        public function refresh()
        {
            $id = json_decode(file_get_contents('php://input'), true);

            $data = array(
                    'client_id' => $id,
                    'accounts' => $this->account_model->getAccountsByClient($id),
                    'info' => $this->client_model->getClient($id)
                    );
            echo json_encode($data);
        }

        public function deletePurchase()
        {
            $id = json_decode(file_get_contents('php://input'), true);
            $this->account_model->deletePurchase($id);
        }

        public function deleteAccount()
        {
            $id = json_decode(file_get_contents('php://input'), true);
            $this->account_model->deleteAccount($id);
            echo 1;
        }

        public function updateContactInfo()
        {
            $info = json_decode(file_get_contents('php://input'), true);
            if($this->client_model->updateContactInfo($info))
            {
                if($this->client_model->updateClient($info))
                    echo 1;
            }
        }

        public function verifyUserName()
        {
            $name = json_decode(file_get_contents('php://input'), true);
            if($this->client_model->verifyName($name['user_name']))
            {
                echo 1;
            }
        }

        public function register()
        {
            $client = json_decode(file_get_contents('php://input'), true);
            $id = $this->client_model->setClient($client);
            
            if($id)
            {
                $client['id_client'] = $id;
                $account = (object)['id_client' => $id, 'id_type' => $client['account_type'], 'balance' => 0];
                $idAccount = $this->account_model->setAccount($account);
                $this->client_model->setContactInfo($client);
                $purchase = array('id_account' => $idAccount, 'source' => '-', 'amount' => 0, 'date' => date("Y-m-d"), 'cur_bal' => 0);
                $this->account_model->setPurchase($purchase);
                $data = array(
                    'client_id' => $id,
                    'accounts' => $this->account_model->getAccountsByClient($id),
                    'info' => $this->client_model->getClient($id)
                    );
                echo json_encode($data);
            }
        }

        public function addClientInfo()
        {
            $info = json_decode(file_get_contents('php://input'), true);

            if($this->client_model->setContactInfo($info))
            {
                echo $client->name;
            }
        }

       
}