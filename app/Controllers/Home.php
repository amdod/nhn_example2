<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		return view('bookview');
	}

	public function bookcreate()
	{
		return view('bookcreate');
	}

	public function bookmodify()
	{
		return view('bookmodify');
	}
	//--------------------------------------------------------------------

}
