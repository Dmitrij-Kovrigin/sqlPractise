<?php

namespace Rubu\Parduotuve\Controllers;

use Rubu\Parduotuve\App;

class RubuController
{

    const IN_PAGE = 30;

    public static function outfitsTypes()
    {
        // SELECT DISTINCT column1, column2, ...
        // FROM table_name;
        $sql = "SELECT DISTINCT `type`
        FROM outfits
        ORDER BY `type`
        ";
        $stmt = App::$pdo->query($sql);
        $types = $stmt->fetchAll();
        return $types;
    }

    public static function allTags()
    {
        $sql = "SELECT *
        FROM tags
        ORDER BY title
        ";
        $stmt = App::$pdo->query($sql);
        $tags = $stmt->fetchAll();
        return $tags; // tag title ir tag id
    }

    // public static function sizesTypes()
    // {
    //     $sql = "SELECT DISTINCT dydis
    //     FROM rubai
    //     ORDER BY dydis
    //     ";
    //     $stmt = App::$pdo->query($sql);
    //     $sizes = $stmt->fetchAll();
    //     return $sizes;
    // }

    public static function countAllProducts()
    {
        // SELECT COUNT(ProductID) AS NumberOfProducts FROM Products;
        $sql = "SELECT COUNT(id) AS number_of_products
        FROM outfits
        ";
        $stmt = App::$pdo->query($sql);
        return $stmt->fetch()['number_of_products'];
    }



    public function selectTest()
    {

        $sql = "SELECT
        o.id, `type`, color, price, discount,
        (price - discount) AS total_price,
        GROUP_CONCAT(DISTINCT(t.title)) as tags_list,
        GROUP_CONCAT(DISTINCT(s.size)) as sizes_list,
        GROUP_CONCAT(s.amount) as amounts_list
        FROM
        outfits as o
        INNER JOIN outfits_tags as ot
        ON o.id = ot.outfit_id
        INNER JOIN tags as t
        ON ot.tag_id = t.id
        INNER JOIN sizes as s
        ON o.id = s.outfit_id
        GROUP BY o.id
        ORDER BY total_price DESC
        ";
        $stmt = App::$pdo->query($sql);
        $outfits = $stmt->fetchAll();
        foreach ($outfits as &$outfit) {
            $outfit['tags_list'] = explode(',', $outfit['tags_list']);
            $outfit['sizes_list'] = explode(',', $outfit['sizes_list']);
            $outfit['amounts_list'] = explode(',', $outfit['amounts_list']);
            $outfit['sizes_amounts'] = [];
            foreach ($outfit['sizes_list'] as $index => $size) {
                $outfit['sizes_amounts'][$size] = $outfit['amounts_list'][$index];
            }
            unset($outfit['sizes_list'], $outfit['amounts_list']);
        }


        App::view('list', [
            'outfits' => $outfits,
            // 'types' => $types,
            // 'count' => $productsCount,
            'in_one_page' => self::IN_PAGE,
            // 'fullTagsList' => $fullTagsList
            // 'sizes' => $sizes,
            // 'count_all' => $countAll
        ]);
    }



    public function edit()
    {
        $sql = "SELECT
        o.id, `type`, color, price, discount,
        (price - discount) AS total_price,
        GROUP_CONCAT(DISTINCT(t.title)) as tags_list,
        GROUP_CONCAT(DISTINCT(s.size)) as sizes_list,
        GROUP_CONCAT(s.amount) as amounts_list
        FROM
        outfits as o
        LEFT JOIN outfits_tags as ot
        ON o.id = ot.outfit_id
        LEFT JOIN tags as t
        ON ot.tag_id = t.id
        INNER JOIN sizes as s
        ON o.id = s.outfit_id
        GROUP BY o.id
        ORDER BY total_price DESC
        ";
        $stmt = App::$pdo->query($sql);
        $outfits = $stmt->fetchAll();
        foreach ($outfits as &$outfit) {
            $outfit['tags_list'] = explode(',', $outfit['tags_list']);
            $outfit['sizes_list'] = explode(',', $outfit['sizes_list']);
            $outfit['amounts_list'] = explode(',', $outfit['amounts_list']);
            $outfit['sizes_amounts'] = [];
            foreach ($outfit['sizes_list'] as $index => $size) {
                $outfit['sizes_amounts'][$size] = $outfit['amounts_list'][$index];
            }
            unset($outfit['sizes_list'], $outfit['amounts_list']);
        }
        $types = self::outfitsTypes();
        $productsCount = self::countAllProducts();
        $allTags = self::allTags();
        App::view('edit', [
            'outfits' => $outfits,
            'types' => $types,
            'count' => $productsCount,
            'in_one_page' => self::IN_PAGE,
            'allTags' => $allTags
        ]);
    }

    public function addSize(int $id)
    {
        $size = $_POST['new_size'];
        // Pradinis kiekis = 0
        // INSERT SQL


        $sql = "INSERT INTO
        sizes
        (size, amount, outfit_id)
        VALUES ('$size', 0, $id)
        ";


        App::$pdo->query($sql);
        App::redirect('edit');
    }

    public function addTag(int $id)
    {
        $tagId = (int) $_POST['tag'];
        if (0 === $tagId) {
            // Kurti nauja taga
            // Naujo insert
            $tagTitle = $_POST['new_tag'];
            $sql = "INSERT INTO
            tags
            (title)
            VALUES ('$tagTitle')
            ";
            App::$pdo->query($sql);

            $sql = "SELECT id
            FROM
            tags
            WHERE title = '$tagTitle'
            ";
            $stmt = App::$pdo->query($sql);
            $tagId = $stmt->fetch()['id'];
        }
        // INSERT kuris padarytu rysy tarp tago ir prekes
        $sql = "INSERT INTO
        outfits_tags
        (outfit_id, tag_id)
        VALUES ($id, $tagId)
        ";
        App::$pdo->query($sql);

        App::redirect('edit');
    }



    public function removeTag(int $id)
    {
        $tagsTitle = $_POST['remove_tag'] ?? [];

        if (empty($tagsTitle)) {
            App::redirect('edit');
        }

        // $tagsTitle masyvas su tagų vardais
        // $id prekės id iš kurios reikia trinti

        $tagIds = implode(',', array_map(fn ($v) => "'$v'", $tagsTitle));

        // $sql = "SELECT
        // GROUP_CONCAT(id) as tag_ids
        // FROM
        // tags
        // WHERE title IN ( $tagIds )
        // ";

        // $stmt = App::$pdo->query($sql);
        // $tagIds = $stmt->fetch()['tag_ids'];

        $sql = "DELETE FROM
        outfits_tags
        WHERE outfit_id = $id AND tag_id IN ( SELECT
        id
        FROM
        tags
        WHERE title IN ( $tagIds )
         )
        ";

        $stmt = App::$pdo->query($sql);

        App::redirect('edit');
        // echo '<pre>';
        // print_r($tagIds);
        // die;



        //1 SELECTAS gražinantis tagu id pagal tagu varda masyve
        //2 DELETAS pagal tago ir prekės idsus 



    }

    public function showTags()
    {
        App::view('tags', [
            'tags' => self::allTags()
        ]);
    }

    public function updateTag($id)
    {
        $title = $_POST['title'];
        //-------> SQL
        $sql = "UPDATE
        tags
        SET title = '$title'
        WHERE id = $id
        ";
        App::$pdo->query($sql);
        App::redirect('tags');
    }


    public function update(int $id)
    {
        // _d($_POST);

        $price = (float) $_POST['price'];
        $discount = (float) $_POST['discount'];
        //-----> turim tris dalykus

        //-------> SQL
        $sql = "UPDATE
        outfits
        SET price = $price, discount = $discount
        WHERE id = $id
        ";
        App::$pdo->query($sql);

        $sizes = array_map(fn ($s) => (int) $s, $_POST['size']);

        foreach ($sizes as $size => $amount) {

            //-------> SQL
            $sql = "UPDATE
            sizes
            SET amount = $amount
            WHERE size = '$size' AND outfit_id = $id
            ";
            App::$pdo->query($sql);
        }

        // Delete

        if (isset($_POST['delete_size'])) {
            foreach ($_POST['delete_size'] as $size) {

                //-------> Trynimo SQL
                $sql = "DELETE FROM
                sizes
                WHERE size = '$size' AND outfit_id = $id
                ";
                App::$pdo->query($sql);
            }
        }



        App::redirect('edit');
    }

    public function buy()
    {
        $id = (int) $_POST['id'];
        $count = (int) $_POST['count'];
        $size = $_POST['size'];


        $sql = "SELECT
        amount
        FROM
        sizes
        WHERE size = '$size' && outfit_id = $id
        ";

        $sql = "SELECT
        amount
        FROM
        outfits
        INNER JOIN sizes
        ON outfits.id = sizes.outfit_id
        WHERE size = '$size' AND  outfits.id = $id
        ";

        $stmt = App::$pdo->query($sql);
        $kiekis = $stmt->fetch()['amount'];


        $kiekis -= $count;

        $kiekis = $kiekis < 1 ? 'NULL' : $kiekis;

        $sql = "UPDATE
        sizes
        SET amount = $kiekis
        WHERE outfit_id = $id AND size = '$size'
        ";

        App::$pdo->query($sql);

        App::redirect('test');
    }

    public static function countAll($sql)
    {
        $sql = str_replace('GROUP BY o.id', '', $sql);
        $sqlList = "SELECT COUNT(DISTINCT(o.id)) AS all_products, (price - discount) AS total_price
        FROM
        outfits as o
        LEFT JOIN outfits_tags as ot
        ON o.id = ot.outfit_id
        LEFT JOIN tags as t
        ON ot.tag_id = t.id
        INNER JOIN sizes as s
        ON o.id = s.outfit_id
        ";
        $stmt = App::$pdo->query($sqlList . $sql);
        return $stmt->fetch()['all_products'];
    }


    public function list()
    {
        $sqlList = "SELECT
        o.id, `type`, color, price, discount,
        (price - discount) AS total_price,
        GROUP_CONCAT(DISTINCT(t.title)) as tags_list,
        GROUP_CONCAT(DISTINCT(s.size)) as sizes_list,
        GROUP_CONCAT(s.amount) as amounts_list
        FROM
        outfits as o
        LEFT JOIN outfits_tags as ot
        ON o.id = ot.outfit_id
        LEFT JOIN tags as t
        ON ot.tag_id = t.id
        INNER JOIN sizes as s
        ON o.id = s.outfit_id
        ";

        //Didysis ifinimas

        if (isset($_GET['type']) && $_GET['type'] != 'default') {
            $type = $_GET['type'];

            if (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') {
                $sql = "
                WHERE o.type = '$type'
                GROUP BY o.id
                ORDER BY total_price
                ";
            } elseif (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') {
                $sql = "
                WHERE o.type = '$type'
                GROUP BY o.id
                ORDER BY total_price DESC
                ";
            } else {
                $sql = "
                WHERE o.type = '$type'
                GROUP BY o.id
                ";
            }
        } else {
            if (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') {
                $sql = "
                GROUP BY o.id
                ORDER BY total_price
                ";
            } elseif (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') {
                $sql = "
                GROUP BY o.id
                ORDER BY total_price DESC
                ";
            } else {
                $sql = "
                GROUP BY o.id
                ";
            }
        }

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $inPage = self::IN_PAGE;
        $offset = ($page - 1) * $inPage;
        $limit = "
        LIMIT $offset , $inPage";


        $stmt = App::$pdo->query($sqlList . $sql . $limit);
        $outfits = $stmt->fetchAll();
        foreach ($outfits as &$outfit) {
            $outfit['tags_list'] = explode(',', $outfit['tags_list']);
            $outfit['sizes_list'] = explode(',', $outfit['sizes_list']);
            $outfit['amounts_list'] = explode(',', $outfit['amounts_list']);
            $outfit['sizes_amounts'] = [];
            foreach ($outfit['sizes_list'] as $index => $size) {
                $outfit['sizes_amounts'][$size] = $outfit['amounts_list'][$index];
            }
            unset($outfit['sizes_list'], $outfit['amounts_list']);
        }
        $types = self::outfitsTypes();
        $productsCount = self::countAll($sql);
        $allTags = self::allTags();
        App::view('list', [
            'outfits' => $outfits,
            'types' => $types,
            'count' => $productsCount,
            'in_one_page' => self::IN_PAGE,
            'allTags' => $allTags
        ]);



        die;







        if (isset($_GET['sort_price_asc'])) {
            $sql = "SELECT
            id, `type`, color, price, discount, (price - discount) AS total_price
            FROM
            outfits
            ORDER BY total_price
            ";
        } elseif (isset($_GET['sort_price_desc'])) {
            $sql = "SELECT
            id, `type`, color, price, discount, (price - discount) AS total_price
            FROM
            outfits
            ORDER BY total_price DESC
            ";
        } elseif (isset($_GET['filter_by_type'])) {
            $rubas = $_GET['rubas'];

            $sql = "SELECT
            id, `type`, color, price, discount, (price - discount) AS total_price
            FROM
            outfits
            WHERE `type` = '$rubas'
            ";
        }
        // Pageris parašyti LIMIT
        elseif (isset($_GET['page'])) {
            $page = $_GET['page'];
            $inPage = self::IN_PAGE;
            $offset = ($page - 1) * $inPage;

            $sql = "SELECT
            id, `type`, color, price, discount, (price - discount) AS total_price
            FROM
            outfits
            LIMIT $offset , $inPage
            ";
        } elseif (isset($_GET['filter_by_size'])) {

            $sizes = implode(',', array_map(fn ($v) => "'$v'", $_GET['size']));

            $sql = "SELECT
            id, rubas, dydis, spalva, kaina, nuolaida, (kaina - nuolaida) AS pardavimo_kaina, kiekis
            FROM
            rubai
            WHERE dydis IN ( $sizes )
            ";
        } elseif (isset($_GET['search'])) {

            $s = $_GET['s'];
            $s = explode(' ', $s);

            if (count($s) == 1) {
                $z = $s[0];
                $sql = "SELECT
                id, `type`, color, price, discount, (price - discount) AS total_price
                FROM
                outfits
                WHERE   color LIKE '%$z%' OR `type` LIKE '%$z%'
                ";
            } else {

                $z1 = $s[0];
                $z2 = $s[1];

                $sql = "SELECT
                id, `type`, color, price, discount, (price - discount) AS total_price
                FROM
                outfits
                WHERE   (color LIKE '%$z2%' AND `type` LIKE '%$z1%')
                        OR 
                        (color LIKE '%$z1%' AND `type` LIKE '%$z2%')
                ";
            }
        } else {
            $sql = "SELECT
            id, `type`, color, price, discount, (price - discount) AS total_price
            FROM
            outfits
            ";
        }


        $stmt = App::$pdo->query($sql);
        $outfits = $stmt->fetchAll();

        $types = self::outfitsTypes();
        $productsCount = self::countAllProducts();
        // $countAll = self::countAll();
        // $sizes = self::sizesTypes();

        App::view('list', [
            'outfits' => $outfits,
            'types' => $types,
            'count' => $productsCount,
            'in_one_page' => self::IN_PAGE,
            // 'sizes' => $sizes,
            // 'count_all' => $countAll
        ]);
    }
}

/*
1. Išrūšiuoti pagal kainą nuo mažiausio
2. Išrūšiuoti pagal kainą nuo didžiausio
3. Parodyti 5 pačias pigiausias
4. Parodyti visas didesnias nei l
5. Parodyti kelnias pigesnias nei 50 išrikiuotas nuo mažiausio
6. Parodyti marškinius xl dydžio pigesnius nei 60
7. Parodyti 5 pigiausius sijonus
8. Parodyti 5 brangiausius sukneles išrikiuotas nuo braugiausios
9. Parodyti visus marškinius ir kelnias ir džinsus kurie yra xl ir nebrangesni nei 50
10. Parodyti xl prekes išrūšiuotas pagal pavadinimą

*/