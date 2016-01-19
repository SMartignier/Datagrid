<div id="list">

<form action="<?php echo htmlentities($_SERVER["REQUEST_URI"]);?>" method="get">
<select name="category_id" onchange="this.form.submit();">
<option value="0">--- Tout ---</option><?php
$query = new Core\Db\QueryBuilder();
$sql = $query->select()->from('articlescategories')->getSql(); // "SELECT * FROM articlescategories";
$categories = App::db()->fetch($sql);
foreach($categories as $category){?>
	<option value="<?php echo $category["id"];?>"<?php if($category["id"] == @$_GET["category_id"]) echo 'selected="selected"';?>><?php echo $category["category"];?></option><?php
}?>
</select>
</form>

<table><?php
$query = new Core\Db\QueryBuilder();
$sql = $query->select()->from('articlescategories');
if(@$_GET["category_id"] != 0)
	$sql = $sql->where('id='.$_GET["category_id"]);
$sql = $sql->getSql(); 
$categories = App::db()->fetch($sql);
foreach($categories as $category){?>
	<tr><td colspan="4"><h2><?php echo $category["category"];?></h2></td></tr>
	<tr>
		<th>Image</th>
		<th>Titre</th>
		<th>Prix</th>
		<th>Panier</th>
	</tr><?php
	$query = new Core\Db\QueryBuilder();
	$sql = $query->select()->from('articles')->where('category_id='.$category["id"])->getSql();
	$articles = App::db()->fetch($sql);
	foreach($articles as $article){?>
		<tr>
			<td><img src="assets/images/articles/<?php echo $article["picture"];?>" alt="<?php echo $article["title"];?>" /></td>
			<td><a href="view?id=<?php echo $article["id"];?>"><?php echo $article["title"];?></a></td>
			<td class="prix"><?php echo number_format($article["price"], 2);?></td>
			<td><a href="basket?action=add&amp;id=<?php echo $article["id"];?>">Ajouter au panier</a></td>
		</tr><?php
	}
}?>
</table>

</div>