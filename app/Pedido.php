<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Pedido extends Model
{
    //
    use Sortable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_pedido';

    public $sortable = ['id', 'numero', 'Cliente.nome', 'id_cliente', 'PedidoStatus.descricao', 'valor', 'created_at'];

    public function Produtos() {
        return $this->belongsToMany('App\Produto', 'tbl_pedido_item', 'id_pedido', 'id_produto')->as('PedidoItem')->withPivot('valor', 'quantidade')->withTimestamps();
    }

    public function PedidoStatus() {
        return $this->belongsTo('App\PedidoStatus', 'id_pedido_status', 'id'); //->as('Status')->withPivot('id', 'descricao')->withTimestamps();
    }

    public function Cliente() {
        return $this->belongsTo('App\Cliente', 'id_cliente', 'id');
    }

}
