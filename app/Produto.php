<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Produto extends Model
{
    //
    use Sortable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_produto';

    public $sortable = ['id', 'nome', 'descricao', 'valor', 'ativo', 'created_at'];

    public function Pedido() {
        return $this->belongsToMany('App\Pedido', 'tbl_pedido_item', 'id_produto', 'id_pedido')->as('PedidoItem')->withPivot('valor', 'quantidade')->withTimestamps();
    }

}
