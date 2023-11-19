<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'text',
        'link',
        'title',
        'clickable',
        'handler',
        'attributes',
        'parent_id',
        'position'
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function subitems($orderBy = 'position', $trend = 'asc')
    {
        $trend = strtolower($trend);
        if ($trend == 'desc') {
            return $this->hasMany(MenuItem::class, 'parent_id')->orderByDesc($orderBy);
        }
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy($orderBy);
    }

    public static function tree($parent = null)
    {
        if ($parent !== null) {
            $parent = intval($parent);
            if ($parent == 0) {
                $parent = null;
            }
        }
        $result = [];
        $result = self::where('parent_id',$parent)->get();
        if (!empty($result)) {
            foreach ($result as $i => $item) {
                $sublevelData = self::tree($item->id);
                if (!empty($sublevelData)) {
                    $result[$i]['children'] = $sublevelData;
                }
            }
        }

        return $result;
    }

    public static function flat($parent = null, $level = 0, $trend = 'asc', $order = 'id', $count = 100, $search = '', $menu_id = null)
    {
        /* $trend = 'asc';
        $order = 'id'; */
        if (!empty($trend) && $trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($order)) {
            $order = $order;
        }
        if (!empty($count)) {
            $count = intval($count);
        }
        if (empty($count)) {
            $count = 100;
        }
        if ($parent !== null) {
            $parent = intval($parent);
            if ($parent == 0) {
                $parent = null;
            }
        }
        $result = [];
        //$data = self::where('parent_id',$parent)->get();
        if (!empty($search)) { //only for 0 level
            $data = self::where('parent_id',$parent)->where('name','like',"%{$search}%")->orderBy($order, $trend)->paginate($count);
        }
        elseif (!empty($menu_id)) {
            $data = self::where('menu_id',$menu_id)->where('parent_id',$parent)->orderBy($order, $trend)->paginate($count);
        }
        else {
            $data = self::where('parent_id',$parent)->orderBy($order, $trend)->paginate($count);
        }

        if (!empty($data)) {
            foreach ($data as $item) {
                $item['level'] = $level;
                $result[] = $item;
                $sublevelData = self::flat(parent: $item->id, level: $level+1);
                if (!empty($sublevelData)) {
                    $result = array_merge($result, $sublevelData);
                }
            }
        }

        return $result;
    }

    public static function children($id, $subchild=false)
    {
        $result = [];
        $data = self::where('parent_id',$id)->get();

        foreach ($data as $item) {
            $result[] = $item;
            if ($subchild) {
                $subresult = self::children($item->id,$subchild);
                if (!empty($subresult)) {
                    $result = array_merge($result, $subresult);
                }
            }

        }

        return $result;
    }

    public static function childrenid($id, $subchild=false)
    {
        $result = [];
        $data = self::where('parent_id',$id)->get('id');

        foreach ($data as $item) {
            $result[] = $item->id;
            if ($subchild) {
                $subresult = self::childrenid($item->id,$subchild);
                if (!empty($subresult)) {
                    $result = array_merge($result, $subresult);
                }
            }

        }

        return $result;
    }
}
