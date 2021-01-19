<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\BookModel;


class BookController extends ResourceController
{
    use ResponseTrait;

	public function index()
	{
        $model = new BookModel();
        $data['events'] = $model->findAll();
        return $this->respond($data);
    }

    public function create()
    {
        $model = new BoardModel();
        $result = $model->insert($_POST);

        return $this->respondCreated($result);
    }

    public function update($number = null)
    {
        $model = new BoardModel();
        
        //if ($this->request->getVar('password') == $model->find($number)->password) {
        $result = $model->update($number, $_POST);

        return $this->respond($result); 
        //}
        //else {
        //    return $this->failNotFound('Incorrect Password;.');
        //}
    }

    public function delete($id = null)
    {
        $model = new BookModel();
        $data = $model->find($id);
        if($data){
            $result = $model->delete($id);
            
            return $this->respondDeleted($result);
        }else{
            return $this->failNotFound('No Data Found with number '.$id);
        }
         
    }

	//--------------------------------------------------------------------

}