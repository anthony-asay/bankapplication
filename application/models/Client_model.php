<?php 
class Client_model extends CI_Model {
	
		public function __construct()
		{
			$this->load->database();
		}

        public function getClient($id)
        {
            $this->db->select('c.*, i.*');
            $this->db->from('client c');
            $this->db->join('contact_info i','c.id = i.id_client', 'left');
            $this->db->where('c.id', $id);
            $user = $this->db->get()->result();
            return $user[0];
        }

        public function getContactInfo($clientId)
        {
            $this->db->select('i.*');
            $this->db->from('contact_info i');
            $this->db->where('i.id_client', $clientId);
            return $this->db->get()->result()[0];   
        }

        public function authenticate($userName, $password)
        {
            $this->db->select('c.*');
            $this->db->from('client c');
            $this->db->where('c.name_user', $userName);
            $this->db->where('c.password', $password);
            $client = $this->db->get()->result();
            if($client)
            {
                return $client[0];
            }
            else
            {
                return false;
            }
        }

        public function getClients()
        {
            $this->db->select('c.*');
            $this->db->from('client c');
            $clients = $this->db->get()->result();
            return $clients;
        }

		public function setClient($client)
        {
            $data = array(
                'name_first' => $client['name_first'],
                'name_last' => $client['name_last'],
                'name_middle' => $client['name_middle'],
                'name_user' => $client['name_user'],
                'password' => $client['password'],
                'date_birth' => $client['date_birth']
                );
	           
            $this->db->insert('client', $data);
            $id = $this->db->insert_id();

            return $id;
        }

        public function verifyName($userName)
        {
            $this->db->select('c.*');
            $this->db->from('client c');
            $this->db->where('c.name_user', $userName);
            $client = $this->db->get();
            if($client->num_rows() >= 1)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public function updateClient($client)
        {
            $data = array(
                'name_first' => $client['name_first'],
                'name_last' => $client['name_last'],
                'name_middle' => $client['name_middle'],
                'name_user' => $client['name_user'],
                'password' => $client['password'],
                'date_birth' => $client['date_birth']
                );
               
            $this->db->update('client', $data, array('id' => $client['id_client']));
            return true;
        }

        public function updateContactInfo($info)
        {
            $data = array(
                'phone_number' => $info['phone_number'],
                'email' => $info['email'],
                'address' => $info['address'],
                'city' => $info['city'],
                'state' => $info['state'],
                'zipcode' => $info['zipcode'],
                'id_client' => $info['id_client']
                );
               
            $this->db->update('contact_info', $data, array('id' => $info['id']));
            return true;
        }

        public function setContactInfo($info)
        {
            $data = array(
                'phone_number' => $info['phone_number'],
                'email' => $info['email'],
                'address' => $info['address'],
                'city' => $info['city'],
                'state' => $info['state'],
                'zipcode' => $info['zipcode'],
                'id_client' => $info['id_client']
                );
               
            $this->db->insert('contact_info', $data);
            $id = $this->db->insert_id();

            return $id;
        }

        public function deleteClient($id)
        {
            $this->db->delete('client', array('id' => $id));  
            $this->db->delete('contact_info', array('id_client' => $id));
            $this->db->delete('account', array('id_client' => $id));
        }
}