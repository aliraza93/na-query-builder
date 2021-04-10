<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;

class NamedPage extends Model
{
  
    protected $connection = 'pgsql3';
    public $timestamps = false;
    protected $attributes = array(
        'html_content' => '<!DOCTYPE html>
        <html lang="en">
          <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Warning</title>
          </head>
          <body>
            <div>
              <h3 style="color: red;">Warning</h3>
            </div>
            <div style="text-align: center;">
              <h4>Unauthorized Access: <span style="color: blue;">TRAFFIC_RESOURCE</span></h4>
              <h4>Please review corporate policies: <span style="color: blue;">POLICY</span></h4>
            </div>
          </body>
        </html>'
     );
    protected $guarded = [];
    public static $validator = [
        'title' => 'required|string',
        'default_page_flag'=> 'nullable|string',
    ];

    protected $table = 'block_page';
    protected $primaryKey = 'block_page_id';
    protected $pk = 'block_page_id';
}
