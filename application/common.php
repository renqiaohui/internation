<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if (!function_exists('addCdataNode')) {
    /**
     * 添加cdata节点
     */
    function addCdataNode($childNode, $value, &$node) {
        /** @var \SimpleXMLElement $node */
        $child = $node->addChild($childNode);
        if ($child !== NULL) {
            $child_node = dom_import_simplexml($child);
            $child_owner = $child_node->ownerDocument;
            $child_node->appendChild($child_owner->createCDATASection($value));
        }
        return $child;
    }
}
