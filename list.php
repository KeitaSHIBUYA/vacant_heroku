<?php//データベース情報の指定$db[ 'dbname' ] = "heroku_921e62bfdd3a5fd"; // データベース名$db[ 'user' ] = "bf76585b664e5b"; // ユーザー名$db[ 'pass' ] = "e925f993"; // ユーザー名のパスワード$db[ 'host' ] = "us-cdbr-east-04.cleardb.com"; // DBサーバのURL//エラーメッセージの初期化$errorMessage = "";//フラグの初期化$o = false;//検索ボタンが押された時の処理if ( isset( $_POST[ "search" ] ) ) {	//入力チェック	if ( empty( $_POST[ "textbox" ] ) ) {		$errorMessage = '未入力です。';	}	if ( !empty( $_POST[ "textbox" ] ) ) {		$o = true;		//入力した文字を変数に格納		$textbox = $_POST[ "textbox" ];		$textboxs = explode( " ", mb_convert_kana( $textbox, 's' ) );		$operator_in = $_POST[ "operator_in" ];		$operator_like = $_POST[ "operator_like" ];		$selectbox = $_POST[ "selectbox" ];		//dsnを作成		$dsn = sprintf( 'mysql:host=%s; dbname=%s; charset=utf8', $db[ 'host' ], $db[ 'dbname' ] );		try {			//PDOを使ってMySQLに接続			$pdo = new PDO( $dsn, $db[ 'user' ], $db[ 'pass' ], array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ) );			//SQLを作成			if ( $operator_like == 'like' ) {				if ( $operator_in == 'in' ) {					foreach ( $textboxs as $textbox ) {						$textboxCondition[] = "(" . $selectbox . " LIKE ?)";						$values[] = '%' . preg_replace( '/(?=[!_%])/', '', $textbox ) . '%';					}					$textboxCondition = implode( ' OR ', $textboxCondition );				} else {					$textboxCondition[] = "(" . $selectbox . " LIKE ?)";					$values[] = '%' . preg_replace( '/(?=[!_%])/', '', $textbox ) . '%';					$textboxCondition = implode( ' OR ', $textboxCondition );				}				$sql = "SELECT * FROM property WHERE $textboxCondition";			} else {				if ( $operator_in == 'in' ) {					$place_holders = implode( ',', array_fill( 0, count( $textboxs ), '?' ) );					$sql = "SELECT * FROM property WHERE $selectbox IN ($place_holders)";				} else {					$sql = "SELECT * FROM property WHERE $selectbox = ?";				}			}			$stmt = $pdo->prepare( $sql );			if ( $operator_like == 'like' ) {				$stmt->execute( $values );			} else {				if ( $operator_in == 'in' ) {					$stmt->execute( $textboxs );				} else {					$stmt->execute( array( $textbox ) );				}			}		} catch ( PDOException $e ) {			$errorMessage = 'データベースエラー';		}	}}?><?php//一ページに表示する記事の数をmax_viewに定数として定義define( 'max_view', 5 );try {	//test@localhostでblogに接続	$pdo = new PDO( 'mysql:dbname=heroku_921e62bfdd3a5fd;host=us-cdbr-east-04.cleardb.com;charset=utf8', 'bf76585b664e5b', 'e925f993' );} catch ( PDOException $error ) {	//エラーの場合はエラーメッセージを吐き出す	exit( "データベースに接続できませんでした。<br>" . $error->getMessage() );}//必要なページ数を求める$count = $pdo->prepare( 'SELECT COUNT(*) AS count FROM property' );$count->execute();$total_count = $count->fetch( PDO::FETCH_ASSOC );$pages = ceil( $total_count[ 'count' ] / max_view );//現在いるページのページ番号を取得if ( !isset( $_GET[ 'page_id' ] ) ) {	$now = 1;} else {	$now = $_GET[ 'page_id' ];}//表示する記事を取得するSQLを準備$select = $pdo->prepare( "SELECT * FROM property ORDER BY no DESC LIMIT :start,:max " );if ( $now == 1 ) { //1ページ目の処理	$select->bindValue( ":start", $now - 1, PDO::PARAM_INT );	$select->bindValue( ":max", max_view, PDO::PARAM_INT );} else { //1ページ目以外の処理	$select->bindValue( ":start", ( $now - 1 ) * max_view, PDO::PARAM_INT );	$select->bindValue( ":max", max_view, PDO::PARAM_INT );} //実行し結果を取り出しておく$select->execute();$data = $select->fetchAll( PDO::FETCH_ASSOC );?><!DOCTYPE html><html lang="ja"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title>情報連携実習III デモサイト vacant</title><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="description" content="情報連携実習IIIで作成"><meta name="keywords" content="レンタルオフィス,シェアオフィス,コワーキング,INIAD,坂村健"><link rel="stylesheet" href="css/style.css"><link rel="stylesheet" href="css/slide.css"><link rel="icon" href="favicon.ico" id="favicon"><link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png"><script src="https://kit.fontawesome.com/258022ddb4.js" crossorigin="anonymous"></script> <script type="text/javascript" src="js/openclose.js"></script> <script type="text/javascript" src="js/fixmenu_pagetop.js"></script> <!-- Bootstrap CSS --> <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">--> <!-- Bootstrap Javascript(jQuery含む) --> <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script><script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>--></head><body class="c2"><div id="container">	<header>		<h1 id="logo"><a href="index.php"><img id="toplogo" src="images/logo_03.png" alt="logo"></a></h1>		<div id="contact"> 			<!--      <p class="tel">掲載のお申し込み</p>-->			<div class="button05"> <a href="form.php"><i class="fa far fa-file-alt"></i>掲載のお申し込み</a> </div>		</div>	</header>		<!--PC用（801px以上端末）メニュー-->	<nav id="menubar">		<ul>			<li><a href="index.php">ホーム<span>HOME</span></a></li>			<li><a href="update.php">更新<span>UPDATE</span></a></li>			<li><a href="process.php">過程<span>PROCESS</span></a></li>			<li><a href="member.php">メンバー<span>MEMBER</span></a></li>			<li><a href="link.php">参考<span>LINK</span></a></li>			<li><a href="list.php">物件一覧<span>LIST</span></a></li>		</ul>	</nav>	<!--小さな端末用（800px以下端末）メニュー-->	<nav id="menubar-s">		<ul>			<li><a href="index.php">ホーム<span>HOME</span></a></li>			<li><a href="update.php">更新<span>UPDATE</span></a></li>			<li><a href="process.php">過程<span>PROCESS</span></a></li>			<li><a href="member.php">メンバー<span>MEMBER</span></a></li>			<li><a href="link.php">参考<span>LINK</span></a></li>			<li><a href="list.php">物件一覧<span>LIST</span></a></li>		</ul>	</nav>	<?php	try {		//test@localhostでblogに接続		$pdo = new PDO( 'mysql:dbname=heroku_921e62bfdd3a5fd;host=us-cdbr-east-04.cleardb.com;charset=utf8', 'bf76585b664e5b', 'e925f993' );	} catch ( PDOException $error ) {		//エラーの場合はエラーメッセージを吐き出す		exit( "データベースに接続できませんでした。<br>" . $error->getMessage() );	}	?>	<div id="contents">		<div id="contents-in">			<div id="main">				<section>					<h2>物件一覧</h2>					<table class="ta1">						<?php						//prepareでSQLを準備						$select = $pdo->prepare( 'SELECT * FROM property' );						$select->execute(); //準備したSQLを実行。PDO Statementクラスのオブジェクトが渡される。(SQLの結果も保持する)						//PDO Statementのfetchメッソドを利用して、結果を一行ずつ取得。データがなくなるとFALSEを返す。						while ( $row = $select->fetch( PDO::FETCH_ASSOC ) ) {							//							$title = htmlspecialchars( $row[ 'property_name' ] ); //タイトルを取得							//							$file_path = htmlspecialchars( $row[ 'id' ] ); //ファイルパスを取得							//							$station = htmlspecialchars( $row[ 'station' ] );							//							$zip = htmlspecialchars( $row[ 'zip' ] );							//							$state = htmlspecialchars( $row[ 'state' ] );							//							$city = htmlspecialchars( $row[ 'city' ] );							//							$street = htmlspecialchars( $row[ 'street' ] );							$title = $row[ 'property_name' ]; //タイトルを取得							$file_path = $row[ 'id' ]; //ファイルパスを取得							$applicant_name = $row['applicant_name'];							$station = $row[ 'station' ];							$zip = $row[ 'zip' ];							$state = $row[ 'state' ];							$city = $row[ 'city' ];							$street = $row[ 'street' ];							// HTMLの出力							echo "<tr><th colspan='2' class='tamidashi'><a href='./property.php?id=$file_path'>$title</a></th></tr>";																					echo "<tr><th>登録者名</th>";							echo "<td>$applicant_name</td></tr>";							echo "<tr><th>物件名</th>";							echo "<td><a href='./property.php?id=$file_path'>$title</a></td></tr>";							echo "<tr><th>最寄駅</th>";							echo "<td>$station</td></tr>";							echo "<tr><th>住所</th>";							echo "<td>〒$zip <br> $state $city $street</td></tr>";						}						?>					</table>				</section>				<div style="text-align:center ">				</div>			</div>			<!--/#main-->						<div id="sub">				<nav class="box1">					<h2>物件検索</h2>					<ul class="submenu">						<li>							<?php //②検索フォーム ?>							<form id="searchForm" name="searchForm" action="" method="POST">								<div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>								<div>									<input type="checkbox" name="operator_like" value="like" <?php if($_POST["operator_like"]=="like"){echo "checked";} ?> >									あいまい検索 </div>								<div>									<input type="checkbox" name="operator_in" value="in" <?php if($_POST["operator_in"]=="in"){echo "checked";} ?> >									複数検索(スペース区切り) </div>								<div>									<select name="selectbox">										<option value="station" <?php if($_POST["selectbox"]=="station"){echo "selected";} ?>>最寄駅</option>										<option value="state"<?php if($_POST["selectbox"]=="state"){echo "selected";} ?>>都道府県</option>									</select>									<input type="text" id="textbox" name="textbox" placeholder="最寄駅を入力" value="<?php if (!empty($_POST["textbox"])) {echo htmlspecialchars($_POST["textbox"], ENT_QUOTES);} ?>">									<input type="submit" id="search" name="search" value="検索">								</div>							</form>						</li>					</ul>					<br>					<!-- ここでPHPのforeachを使って結果をループさせる -->					<ul>						<?php						if ( $stmt ) {							$ct = 0;							echo '<h2>検索結果</h2>';							//							echo '<div>物件名：</div>';							echo '<ul class="submenu">';							//							echo '- - - - - - - - - - - - - - - - - - - - - - - - </p>';							foreach ( $stmt as $row ) {								if ( $ct == 0 ) {}								$id = $row[ 'id' ];								//								echo '<li></li>';								//			echo '<div>物件ID：' . $row[ 'id' ] . '</div>';								echo '<li>' . '<a href=./property.php?id=' . $id . '>' . $row[ 'property_name' ] . '</a>' . '</li>';								//								echo '<li>最寄駅：' . $row[ 'station' ] . '</li>';								//								echo '<li>住所：' . '〒' . $row[ 'zip' ] . "<br>" . $row[ 'state' ] . $row[ 'city' ] . $row[ 'street' ] . '</li>';								//								echo '<p>- - - - - - - - - - - - - - - - - - - - - - - - </p>';								$ct++;							}							echo '</ul>';							if ( $ct == 0 ) {								echo '<div>該当するデータはありません</div>';							}						} else {}						?>					</ul>				</nav>				<div class="box1">					<h2>アクセス</h2>					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3236.799789567791!2d139.71335761526143!3d35.7802940801713!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6018931934050759%3A0x239ff01917c6ae5a!2z5p2x5rSL5aSn5a2mIOaDheWgsemAo-aQuuWtpumDqCAoSU5JQUQp!5e0!3m2!1sja!2sjp!4v1633841676994!5m2!1sja!2sjp" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>					<p> 〒115-8650 <br>						東京都北区赤羽台１丁目７−１１<br>						TEL：03-5924-2100<br>						受付：9:00～16:00<br>						定休日：土日祝</p>				</div>			</div>			<!--/#sub--> 					</div>		<!--/#contents-in-->				<div id="side">			<nav class="box1">				<h2>Contents</h2>				<ul class="submenu">					<li><a href="#">メニューサンプル</a></li>					<li><a href="#">メニューサンプル</a></li>					<li><a href="#">メニューサンプル</a></li>					<li><a href="#">メニューサンプル</a></li>					<li><a href="#">メニューサンプル</a></li>				</ul>			</nav>			<nav>				<h2>Contents</h2>				<ul class="submenu">					<li><a href="#">メニューサンプル</a></li>					<li><a href="#">メニューサンプル</a></li>					<li><a href="#">メニューサンプル</a></li>					<li><a href="#">メニューサンプル</a></li>					<li><a href="#">メニューサンプル</a></li>				</ul>				<ul class="submenu">					<li><a href="#">メニューサンプル</a></li>					<li><a href="#">メニューサンプル</a></li>					<li><a href="#">メニューサンプル</a></li>					<li><a href="#">メニューサンプル</a></li>					<li><a href="#">メニューサンプル</a></li>				</ul>			</nav>			<p> <a href="member.php"><img src="images/banner1.jpg" alt="採用情報" class="pc"></a> <a href="member.php"><img src="images/banner1_sh.jpg" alt="採用情報" class="sh"></a> </p>			<p>上のバナー画像は、801px以上の端末と800px以下とで画像２種類が切り替わります。<br>				<a href="process.php#banner">詳しい説明はこちら。</a></p>			<div>				<h2>ボックスの外も</h2>				<p>使えます。サンプルテキスト。サンプルテキスト。</p>			</div>		</div>		<!--/#side--> 			</div>	<!--/#contents--> 	</div><!--/#container--><footer>	<div id="footermenu">		<ul>			<li><a href="update.php">更新</a></li>		</ul>		<ul>			<li><a href="process.php">過程</a></li>		</ul>		<ul>			<li><a href="member.php">メンバー</a></li>		</ul>		<ul>			<li><a href="link.php">参考</a></li>		</ul>		<ul>			<li><a href="list.php">物件一覧</a></li>		</ul>	</div>	<!--/footermenu-->		<div id="copyright"> <small>Copyright&copy; <a href="index.php">Vacant inc.</a> All Rights Reserved.</small> <span class="pr"><a href="http://template-party.com/" target="_blank">《Web Design:Template-Party》</a></span> </div></footer><p class="nav-fix-pos-pagetop"><a href="#">↑</a></p><!--小さな端末用（800px以下端末）メニュー--> <script type="text/javascript">    if (OCwindowWidth() <= 800) {      open_close("newinfo_hdr", "newinfo");    }  </script> <!--メニュー開閉ボタン--><div id="menubar_hdr" class="close"></div><!--メニューの開閉処理条件設定 800px以下--> <script type="text/javascript">    if (OCwindowWidth() <= 800) {      open_close("menubar_hdr", "menubar-s");    }  </script></body></html>