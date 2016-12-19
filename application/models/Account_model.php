<?php 
class Account_model extends CI_Model {
	
		public function __construct()
		{
			$this->load->database();
		}

        public function getAccounts()
        {
            $this->db->select('a.*, t.account_type as type');
            $this->db->from('account a');
            $this->db->join('account_types t', 't.id = a.id_type', 'left');
            $this->db->order_by('a.id','asc');
            $accounts = $this->db->get()->result();

            return $accounts;
        }

        public function getAccount($id)
        {
            $this->db->select('a.*, t.account_type as type');
            $this->db->from('account a');
            $this->db->join('account_types t', 't.id = a.id_type', 'left');
            $this->db->where('a.id',$id);
            $account = $this->db->get()->result();
            return $account;
        }

        public function getAccountsByClient($id)
        {
            $this->db->select('a.*, t.account_type as type');
            $this->db->from('account a');
            $this->db->join('account_types t', 't.id = a.id_type', 'left');
            $this->db->where('a.id_client',$id);
            $accounts = $this->db->get()->result();

            // foreach($accounts as $account)
            // {
            //     $this->db->select('p.*');
            //     $this->db->from('purchases p');
            //     $this->db->where('p.id_account', $account->id);
            //     $this->db->group_by("p.id");
            //     $this->db->order_by('p.date','asc');
            //     $account->purchases = $this->db->get()->result();
            // }

            return $accounts;  
        }

        public function getPurchasesByDate($query)
        {
            $this->db->select('p.*');
            $this->db->from('purchases p');
            $this->db->where('p.id_account', $query['id_account']);
            if($query['days'] != 0)
                $this->db->where('date BETWEEN DATE_SUB(NOW(), INTERVAL '.$query['days'].' DAY) AND NOW()');
            $this->db->group_by("p.id");
            $this->db->order_by('p.date','desc');
            $purchases = $this->db->get()->result();
            return $purchases;
        }

        public function getPurchasesByDay($query)
        {
            $this->db->select('p.*');
            $this->db->from('purchases p');
            $this->db->where('p.id_account', $query['id_account']);
            $this->db->where('p.date >=', $query['date']);
            $this->db->where('p.date <=', $query['endDate']);
            $this->db->group_by("p.id");
            $this->db->order_by('p.date','desc');
            $purchases = $this->db->get()->result();
            return $purchases;
        }


        public function getAccountsByType($typeId)
        {
            $this->db->select('a.*, t.account_type as type');
            $this->db->from('account a');
            $this->db->join('account_types t', 't.id = a.id_type', 'left');
            $this->db->where('a.id_type',$typeId);
            $this->db->order_by('a.id','asc');
            $accounts = $this->db->get()->result();

            foreach($accounts as $account)
            {
                $this->db->select('p.*');
                $this->db->from('purchases p');
                $this->db->where('p.id_account', $account->id);
                $this->db->group_by("p.id");
                $this->db->order_by('p.date','asc');
                $account->purchases = $this->db->get()->result();
            }

            return $accounts;
        }

        public function getTypes()
        {
            $this->db->select('t.*');
            $this->db->from('account_types t');
            $this->db->order_by('t.id','asc');
            return $this->db->get()->result();
        }


		public function setAccount($account)
        {
            $this->db->select('a.*, t.account_type as type');
            $this->db->from('account a');
            $this->db->join('account_types t', 't.id = a.id_type', 'left');
            $this->db->order_by('a.id','asc');
            $accounts = $this->db->get()->result();
            //var_dump($accounts);var_dump(end($accounts));
            $number = end($accounts)->account_number + 1;

            $data = array(
                'id_client' => $account->id_client,
                'id_type' => $account->id_type,
                'account_number' => $number,
                'balance' => $account->balance
                );
            $this->db->insert('account', $data);
            return $this->db->insert_id();
        }

        public function setPurchase($purchase)
        {
            $data = array(
                'date' => $purchase['date'],
                'id_account' => $purchase['id_account'],
                'source' => $purchase['source'],
                'amount' => $purchase['amount'],
                'cur_bal' => $purchase['cur_bal']
                );
            $this->db->insert('purchases', $data);
            return true;   
        }

        public function updatePurchase($purchase)
        {
            $data = array(
                'date' => $purchase->date,
                'id_account' => $purchase->account_id,
                'source' => $purchase->source,
                'amount' => $purchase->amount,
                'cur_bal' => $purchase['cur_bal']
                );
            $this->db->where('id', $purchase->id);
            $this->db->update('purchases', $data);
            return true;   
        }

        public function updateAccount($account)
        {
            $data = array(
                'id_client' => $account->id_client,
                'id_type' => $account->id_type,
                'account_number' => $account->account_number,
                'balance' => $account->balance
                );
            $this->db->where('id', $account->id);
            $this->db->update('account', $data);
            return true;
        }

        public function deleteAccount($id)
        {
            $this->db->delete('account', array('id' => $id));
            $this->db->delete('purchases', array('id_account' => $id)); 
        }

        public function deletePurchase($id)
        {
            $this->db->delete('purchases', array('id' => $id));    
        }
}