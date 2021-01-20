<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\BookModel;


class BookController extends ResourceController
{
    use ResponseTrait;

	public function dateselect($start, $end)
	{
        $model = new BookModel();
        $start_time = $start." 00:00:00";
        $end_time = $end." 23:59:59";

        $data['events'] = $model->where('use_date >=', $start_time)->where('use_date <=', $end_time)->find();
        
        return $this->respond($data);
    }

    public function book($id = null)
	{
        $model = new BookModel();
        $data['events'] = $model->find($id);
        return $this->respond($data);
    }

    public function create()
    {
        $model = new BookModel();
        $result = $model->insert($_POST);

        return $this->respondCreated($result);
    }

    public function update($id = null)
    {
        $model = new BookModel();
        
        //if ($this->request->getVar('password') == $model->find($number)->password) {
        $result = $model->update($id, $_POST);

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