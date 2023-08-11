<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Cliente extends Model
{
    //
    use Sortable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_cliente';

    public $sortable = ['id', 'nome', 'cpf', 'telefone', 'endereco', 'numero'];

    /**
     * Get the relation record associated with the user.
     */
    public function User()
    {
        return $this->belongsTo('App\User', 'id_usuario', 'id');
    }

    /**
     * Get the cliente's cpf.
     *
     * @param  string  $value
     * @return string
     */
    public function getCpfAttribute($value)
    {
        return $value ? MaskText($value, '999.999.999-99') : null;
    }

    /**
     * Get the cliente's telefone.
     *
     * @param  string  $value
     * @return string
     */
    public function getTelefoneAttribute($value)
    {
        $mask = '';
        switch (strlen($value)) {
            case 8: $mask = '9999-9999'; break;
            case 9: $mask = '99999-9999'; break;
            case 10: $mask = '(99) 9999-9999'; break;
            case 11: $mask = '(99) 99999-9999'; break;
            case 12: $mask = '+99 (99) 9999-9999'; break;
            case 13: $mask = '+99 (99) 99999-9999'; break;
        }
        return $value ? MaskText($value, $mask) : null;
    }

}
