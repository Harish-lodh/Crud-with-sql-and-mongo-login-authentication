<?php

namespace App\Controllers;

use App\Models\SqlModel;
use Config\Session;

class Home extends BaseController
{
    protected $sqlModel;
    protected $session;
    protected $mongoApiUrl = "http://localhost:4000";    //Replace with your actual MongoDB API URL

    public function __construct()
    {
        $this->sqlModel = new SqlModel();
        $data = $this->sqlModel->findAll();
        $this->session = \Config\Services::session();
    }


    public function index(): string
    {
        return view('login');
    }
    public function download()
    {
        $data = $this->sqlModel->findAll();
        $filename = 'data_export_' . date('Ymd') . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");
        $file = fopen('php://output', 'w');
        $header = array("ID", "name", "email", "password");
        fputcsv($file, $header);
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
        exit;
    }



    public function insert()
    {
        $validation = \Config\Services::validation();
        $validation->setRules(
            [
                'name' => 'required',
                'email' => 'required|valid_email|is_unique[login.email]',
                'password' => 'required|min_length[8]',
            ],
            [   // Custom error messages
                'email' => [
                    'is_unique' => 'The email address you entered is already registered.',
                    'valid_email' => 'Please enter a valid email address.',
                ],
                'password' => [
                    'min_length' => 'The password must be at least 8 characters long.',
                ],
            ]
        );

        // Get input data
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password')
        ];

        // Validate input data
        if ($validation->run($data)) {
            // Hash the password after validation
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            // Insert data into MySQL
            $this->sqlModel->insert($data);

            // Get the inserted SQL ID
            $insertedId = $this->sqlModel->insertID();

            // Prepare data for MongoDB
            $mongoData = [
                'id' => $insertedId, // Store MySQL ID in MongoDB
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password']
            ];

            // Send data to MongoDB API
            $this->callMongoApi('POST', '/create', $mongoData);

            // Set success message
            session()->setFlashdata('success', 'Registration successful');
            return redirect()->back();
        } else {
            // Get validation errors
            $errors = $validation->getErrors();
            // Set error messages as flashdata
            session()->setFlashdata('error', implode('<br>', $errors));
            return redirect()->back();
        }
    }


    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
       
        $name = $this->sqlModel->where('email', $email)->first()['name'];
        log_message('info',$name);
        // Find the user by email
     
        $this->session->set('admin', $name);
        $user = $this->sqlModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            session()->set('isLoggedIn', true);
            session()->set('user', $user);
            session()->setFlashdata('success', 'Login successful');
            return redirect()->to('/details');
        } else {
            // Failed login
            session()->setFlashdata('error', 'Invalid email or password');
            return redirect()->back();
        }
    }

    public function getUserById($id)
    {
        $user = $this->sqlModel->find($id);

        if ($user) {
            return $this->response->setJSON($user);
        } else {
            return $this->response->setJSON(['error' => 'User not found'], 404);
        }
    }

    public function details()
    {
        // $data = $this->sqlModel->findAll();
        // return view('Details', ['data' => $data]);
   
        $filterName = $this->request->getPost('name');
        $filterEmail = $this->request->getPost('email');

        $alldata = $this->sqlModel->findAll();

        if (!empty($filterName) || !empty($filterEmail)) {


            // Initial query
            $query = $this->sqlModel;
            $name="";
        
            // Apply filters
            if (!empty($filterName)) {
                $name = $query->where('name', $filterName);
            }
            if (!empty($filterEmail)) {
                $email = $query->where('email', $filterEmail);
            }

            // Execute query
            if ($name or $email) {
                $data = $query->findAll();
            };



            $newData = [
                'alldata' => $alldata,
                'data' => $data
            ];

            // print_r($newData['data']);
        } else {
            $newData = [
                'alldata' => $alldata,
                'data' => $alldata
            ];
        }

        return view('Details', $newData);
    }

    public function update($id)
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Hash the password
        ];

        // Update MySQL
        if ($this->sqlModel->update($id, $data)) {
            // Prepare data for MongoDB

            $mongoData = [
                'id' => $id,
                'name' => $data['name'],
                'email' => $data['email'],
                'paasword' => $data['password'],

            ];

            // Update MongoDB via Node.js API
            $this->callMongoApi('PUT', "/update/{$id}", $mongoData);

            //session()->setFlashdata('success', 'User updated successfully');
            return redirect()->to('/details');
        } else {
            session()->setFlashdata('error', 'Failed to update user');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        // Delete from MySQL
        if ($this->sqlModel->delete($id) ) {
            // Delete from MongoDB via Node.js API
            $this->callMongoApi('DELETE', "/delete/{$id}");

            //session()->setFlashdata('success', 'User deleted successfully');
            return redirect()->to('/details');
        } else {
            session()->setFlashdata('error', 'Failed to delete user');
            return redirect()->back();
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    // Helper function to call Node.js API
    private function callMongoApi($method, $endpoint, $data = null)
    {
        $client = \Config\Services::curlrequest();

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        if ($data) {
            $options['body'] = json_encode($data);
        }

        $response = $client->request($method, $this->mongoApiUrl . $endpoint, $options);

        return $response->getStatusCode() === 200 || $response->getStatusCode() === 204;
    }




    public function save()
    {

        $file = $this->request->getFile('file');

        $filepath = $file->getTempName();
        $csvData = array_map('str_getcsv', file($filepath));

        $headers = array_shift($csvData);

        function formatrow($headers, $rows)
        {
            return array_combine($headers, $rows);
        }



        $formatedData = array_map(fn($row) => formatrow($headers, $row), $csvData);

        if (count($formatedData) > 0) {
            for ($i = 0; $i < count($formatedData); $i++) {

                $this->sqlModel->insert($formatedData[$i]);
                $sql_id = $this->sqlModel->insertID();

                $formatedData[$i]['id'] = $sql_id;
                $this->callMongoApi('POST', '/create', $formatedData[$i]);
            }


            // Prepare data for MongoDB
            // $insertedId = $this->sqlModel->insertID();

            session()->setFlashdata('success', 'Registration successful');
            return redirect()->to('/details');
        } else {
            session()->setFlashdata('error', 'Error while sending data');
            return redirect()->back();
        }
    }
}
