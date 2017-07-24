<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExcelImportado extends Model
{
    //
    protected $fillable = ['nombre','mime','tamanio','importado','extencion','ruta','tipo'];
}
