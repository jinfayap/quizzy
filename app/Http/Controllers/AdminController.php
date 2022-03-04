<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function role()
    {
        return view('admin.role');
    }

    public function permission()
    {
        return view('admin.permission');
    }

    public function rolePermission()
    {
        return view('admin.role-permission');
    }

    public function userRolePermission()
    {
        return view('admin.user-role-permission');
    }
}
