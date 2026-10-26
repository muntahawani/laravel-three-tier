<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('/', function () {
    // READ: Database se items fetch karna
    $tasks = DB::table('exam_tasks')->get();
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Laravel 3-Tier CRUD Demo</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; background: #f4f6f9; }
            .card { background: white; padding: 25px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
            h2 { color: #333; }
            input[type="text"] { width: 70%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
            button { padding: 10px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
            button.delete { background: #dc3545; padding: 5px 10px; }
            ul { list-style: none; padding: 0; margin-top: 20px; }
            li { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
            .badge { background: #28a745; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class="card">
            <h2>Laravel 3-Tier CRUD Web App</h2>
            <p><span class="badge">Database Tier Connected: MySQL</span></p>
            <form action="/add" method="POST">
                <input type="text" name="task_name" placeholder="Enter new item name..." required>
                <button type="submit">Create Item</button>
            </form>
            <ul>';
            
    foreach ($tasks as $task) {
        $html .= '<li>' . htmlspecialchars($task->name) . ' 
                    <a href="/delete/' . $task->id . '"><button class="delete">Delete</button></a>
                  </li>';
    }
    
    $html .= '
            </ul>
        </div>
    </body>
    </html>';
    
    return $html;
});

// CREATE: Database me new row add karna
Route::post('/add', function (Request $request) {
    DB::table('exam_tasks')->insert([
        'name' => $request->input('task_name'),
        'created_at' => now()
    ]);
    return redirect('/');
});

// DELETE: Database se row delete karna
Route::get('/delete/{id}', function ($id) {
    DB::table('exam_tasks')->where('id', $id)->delete();
    return redirect('/');
});
