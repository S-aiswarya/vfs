<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Role;
use DB;

class AdminMenu extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $cur_url = \Request::route()->getName();
        $parent = DB::table('admin_links')->join('permissions', 'admin_links.permissions_id', '=', 'permissions.id')->where('permissions.route', $cur_url)->value('parent_id');
        $menu_items = $this->menu_tree(0);

        return view('admin._partials.menu', [
            'menu_items' => $menu_items,
            'parent' => $parent,
            'cur_url' => $cur_url,
            'depth' => 0,
        ]);
    }

    public function menu_tree($parent_id)
    {
        $items = DB::table('admin_links')->select('admin_links.*', 'permissions.name as permission_name', 'permissions.route', 'permissions.id as permission_id')->join('permissions', 'admin_links.permissions_id', '=', 'permissions.id')->where('parent_id', $parent_id)->orderBy('display_order', 'ASC')->get();
        if($items)
        {
            foreach ($items as $key => $item) {
                $check_children = DB::table('admin_links')->select('admin_links.*', 'permissions.name as permission_name', 'permissions.route')->join('permissions', 'admin_links.permissions_id', '=', 'permissions.id')->where('parent_id', $item->id)->count();
                if($check_children>0)
                {
                    $item->children = $this->menu_tree($item->id);
                }
            }
        }
        return $items;
    }
}
