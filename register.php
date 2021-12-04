<?php
//データベース情報の指定
$db[ 'dbname' ] = "heroku_921e62bfdd3a5fd"; // データベース名
$db[ 'user' ] = "bf76585b664e5b"; // ユーザー名
$db[ 'pass' ] = "e925f993"; // ユーザー名のパスワード
$db[ 'host' ] = "us-cdbr-east-04.cleardb.com"; // DBサーバのURL

//エラーメッセージの初期化
$errorMessage = "";
//フラグの初期化
$o = false;

//検索ボタンが押された時の処理
if ( isset( $_POST[ "search" ] ) ) {
	//入力チェック
	if ( empty( $_POST[ "textbox" ] ) ) {
		$errorMessage = '未入力です。';
	}

	if ( !empty( $_POST[ "textbox" ] ) ) {
		$o = true;
		//入力した文字を変数に格納
		$textbox = $_POST[ "textbox" ];
		$textboxs = explode( " ", mb_convert_kana( $textbox, 's' ) );
		$operator_in = $_POST[ "operator_in" ];
		$operator_like = $_POST[ "operator_like" ];
		$selectbox = $_POST[ "selectbox" ];

		//dsnを作成
		$dsn = sprintf( 'mysql:host=%s; dbname=%s; charset=utf8', $db[ 'host' ], $db[ 'dbname' ] );

		try {
			//PDOを使ってMySQLに接続
			$pdo = new PDO( $dsn, $db[ 'user' ], $db[ 'pass' ], array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ) );

			//SQLを作成
			if ( $operator_like == 'like' ) {
				if ( $operator_in == 'in' ) {
					foreach ( $textboxs as $textbox ) {
						$textboxCondition[] = "(" . $selectbox . " LIKE ?)";
						$values[] = '%' . preg_replace( '/(?=[!_%])/', '', $textbox ) . '%';
					}
					$textboxCondition = implode( ' OR ', $textboxCondition );
				} else {
					$textboxCondition[] = "(" . $selectbox . " LIKE ?)";
					$values[] = '%' . preg_replace( '/(?=[!_%])/', '', $textbox ) . '%';
					$textboxCondition = implode( ' OR ', $textboxCondition );
				}
				$sql = "SELECT * FROM property WHERE $textboxCondition";
			} else {
				if ( $operator_in == 'in' ) {
					$place_holders = implode( ',', array_fill( 0, count( $textboxs ), '?' ) );
					$sql = "SELECT * FROM property WHERE $selectbox IN ($place_holders)";
				} else {
					$sql = "SELECT * FROM property WHERE $selectbox = ?";
				}
			}

			$stmt = $pdo->prepare( $sql );
			if ( $operator_like == 'like' ) {
				$stmt->execute( $values );
			} else {
				if ( $operator_in == 'in' ) {
					$stmt->execute( $textboxs );
				} else {
					$stmt->execute( array( $textbox ) );
				}
			}
		} catch ( PDOException $e ) {
			$errorMessage = 'データベースエラー';
		}
	}
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>情報連携実習III デモサイト vacant</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="情報連携実習IIIで作成">
<meta name="keywords" content="レンタルオフィス,シェアオフィス,コワーキング,INIAD,坂村健">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/slide.css">
<link rel="icon" href="favicon.ico" id="favicon">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
<style type="text/css"></style>
<script src="https://kit.fontawesome.com/258022ddb4.js" crossorigin="anonymous"></script> 
<script type="text/javascript" src="js/openclose.js"></script> 
<script type="text/javascript" src="js/fixmenu_pagetop.js"></script> 
<script type="text/javascript" src="js/myscript.js"></script> 

<!-- Bootstrap CSS --> 
<!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">--> 

<!-- Bootstrap Javascript(jQuery含む) --> 
<!--
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
-->

</head>

<body class="c2">
<div id="container">
	<header>
		<h1 id="logo"><a href="index.php"><img id="toplogo" src="images/logo_03.png" alt="logo"></a></h1>
		<div id="contact"> 
			<!--      <p class="tel">掲載のお申し込み</p>-->
			<div class="button05"> <a href="form.php"><i class="fa far fa-file-alt"></i>掲載のお申し込み</a> </div>
		</div>
	</header>
	
	<!--PC用（801px以上端末）メニュー-->
	<nav id="menubar">
		<ul>
			<li><a href="index.php">ホーム<span>HOME</span></a></li>
			<li><a href="update.php">更新<span>UPDATE</span></a></li>
			<li><a href="process.php">過程<span>PROCESS</span></a></li>
			<li><a href="member.php">メンバー<span>MEMBER</span></a></li>
			<li><a href="link.php">参考<span>LINK</span></a></li>
			<li><a href="list.php">物件一覧<span>LIST</span></a></li>
		</ul>
	</nav>
	<!--小さな端末用（800px以下端末）メニュー-->
	<nav id="menubar-s">
		<ul>
			<li><a href="index.php">ホーム<span>HOME</span></a></li>
			<li><a href="update.php">更新<span>UPDATE</span></a></li>
			<li><a href="process.php">過程<span>PROCESS</span></a></li>
			<li><a href="member.php">メンバー<span>MEMBER</span></a></li>
			<li><a href="link.php">参考<span>LINK</span></a></li>
			<li><a href="list.php">物件一覧<span>LIST</span></a></li>
		</ul>
	</nav>
	
	<!--スライドショー--> 
	<!--  <aside id="mainimg"> <img src="images/1.jpg" alt="" class="slide0"> <img src="images/1.jpg" alt="" class="slide1"> <img src="images/2.jpg" alt="" class="slide2"> <img src="images/3.jpg" alt="" class="slide3"> </aside>-->
	
	<div id="contents">
		<div id="contents-in">
			<div id="main">
				<section id="new">
					<h2>物件登録フォーム</h2>
					<div style="text-align: center">
						<?php
						// タイムゾーン設定
						date_default_timezone_set( 'Asia/Tokyo' );
						// 書き込み日時を取得
						$current_date = date( "Y-m-d H:i:s" );

						$pdo = null;

						$host = 'us-cdbr-east-04.cleardb.com';
						$username = 'bf76585b664e5b';
						$passwd = 'e925f993';
						$dbname = 'heroku_921e62bfdd3a5fd';
						$tablename = 'property';

						//	$pdo = new PDO( 'mysql:charset=UTF8;dbname=' . $dbname . ';host=' . $host, $username, $passwd, $option );
						$conn = mysqli_connect( $host, $username, $passwd, $dbname );
						if ( $conn == false ) {
							die( "エラー: 接続できませんでした。" . mysqli_connect_error() );
						}

						$applicant_name = $_REQUEST[ 'applicant_name' ];
						$property_name = $_REQUEST[ 'property_name' ];
						$station = $_REQUEST[ 'station' ];
						$zip = $_REQUEST[ 'zip' ];
						$state = $_REQUEST[ 'state' ];
						$city = $_REQUEST[ 'city' ];
						$street = $_REQUEST[ 'street' ];
						$email = $_REQUEST[ 'email' ];
						$phone = $_REQUEST[ 'phone' ];
						$description = $_REQUEST[ 'description' ];
						$post_date = $current_date;


						$sql = "INSERT INTO $tablename VALUES (null,N'$applicant_name',N'$property_name',N'$station',N'$zip',N'$state',N'$city',N'$street',N'$email',N'$phone',N'$description',N'$post_date')";

						//	$sql = "INSERT INTO $tablename VALUES (null,'東洋太郎','とうようたろう','テストビル','2222222','埼玉県','テスト区','2222222','test@test.com','08011111111','変数の変更と動作確認','$post_date')";

						if ( mysqli_query( $conn, $sql ) ) {
							echo "データベースに正常に登録されました。";
							//							echo nl2br( "\n $applicant_name \n $property_name \n $station \n $zip \n $state \n $city \n $street \n $email \n $phone \n $description \n" );

						} else {
							echo "エラー: $sql. " . mysqli_error( $conn );
						}
						mysqli_close( $conn )

						// データベースの接続を閉じる
						//	$pdo = null;
						?>
					</div>
					
					<!--					<p class="r">&raquo;&nbsp;<a href="#">過去ログ</a></p>--> 
				</section>
				<br>
				<section>
					<table class="ta1">
						<tr>
							<th colspan="2" class="tamidashi" style="text-align: center">登録内容</th>
						</tr>
						<tr>
							<th>申込者名</th>
							<td><?php echo($applicant_name) ?></td>
						</tr>
						<tr>
							<th>物件名</th>
							<td><?php echo($property_name) ?></td>
						</tr>
						<tr>
							<th>最寄駅</th>
							<td><?php echo($station) ?></td>
						</tr>
						<tr>
							<th>住所</th>
							<td><?php echo('〒'. $zip. '  '. $state. $city. $street) ?></td>
						</tr>
						<tr>
							<th>メールアドレス</th>
							<td><?php echo($email) ?></td>
						</tr>
						<tr>
							<th>電話番号</th>
							<td><?php echo($phone) ?></td>
						</tr>
						<tr>
							<th>備考</th>
							<td><?php echo($description) ?></td>
						</tr>
					</table>
					<div style="text-align: center" class="c">
						<button type="button" onclick="history.back()" class="btn">戻る</button>
					</div>
					<br>
				</section>
			</div>
			<!--/#main-->
			
			<div id="sub">
				<nav class="box1">
					<h2>物件検索</h2>
					<ul class="submenu">
						<li>
							<?php //②検索フォーム ?>
							<form id="searchForm" name="searchForm" action="" method="POST">
								<div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
								<div>
								<label class="my-checkbox">
									<input type="checkbox" name="operator_like" value="like" <?php if($_POST["operator_like"]=="like"){echo "checked";} ?> checked>
									<span class="checkmark"></span> あいまい検索
									</div>
								</label>
								<label class="my-checkbox">
								<div>
									<input type="checkbox" name="operator_in" value="in" <?php if($_POST["operator_in"]=="in"){echo "checked";} ?> >
									<span class="checkmark"></span> 複数検索(スペース区切り) </div>
								</label>
								<div class="cp_ipselect cp_sl01">
									<select name="selectbox">
										<option value="station" <?php if($_POST["selectbox"]=="station"){echo "selected";} ?>>最寄駅</option>
										<option value="state"<?php if($_POST["selectbox"]=="state"){echo "selected";} ?>>都道府県</option>
									</select>
								</div>
								<div style="text-align: center;">
									<input type="text" id="textbox" name="textbox" placeholder="最寄駅を入力" value="<?php if (!empty($_POST["textbox"])) {echo htmlspecialchars($_POST["textbox"], ENT_QUOTES);} ?>">
									<div id="search_btn">
										<input type="submit" class="kensaku" id="search" name="search" value="検索">
									</div>
								</div>
							</form>
							<br>
						</li>
					</ul>
					<br>
					<!-- ここでPHPのforeachを使って結果をループさせる -->
					<ul>
						<?php
						if ( $stmt ) {
							$ct = 0;
							echo '<h2>検索結果</h2>';
							//							echo '<div>物件名：</div>';
							echo '<ul class="submenu">';
							//							echo '- - - - - - - - - - - - - - - - - - - - - - - - </p>';
							foreach ( $stmt as $row ) {
								if ( $ct == 0 ) {}
								$id = $row[ 'id' ];
								//								echo '<li></li>';
								//			echo '<div>物件ID：' . $row[ 'id' ] . '</div>';
								echo '<li>' . '<a href=./property.php?id=' . $id . '>' . $row[ 'property_name' ] . '</a>' . '</li>';
								//								echo '<li>最寄駅：' . $row[ 'station' ] . '</li>';
								//								echo '<li>住所：' . '〒' . $row[ 'zip' ] . "<br>" . $row[ 'state' ] . $row[ 'city' ] . $row[ 'street' ] . '</li>';
								//								echo '<p>- - - - - - - - - - - - - - - - - - - - - - - - </p>';
								$ct++;
							}
							echo '</ul>';
							if ( $ct == 0 ) {
								echo '<div>該当するデータはありません</div>';
							}
						} else {}
						?>
					</ul>
				</nav>
				<div class="box1">
					<h2>おすすめ物件情報</h2>
					<?php
					try {
						//test@localhostでblogに接続
						$pdo = new PDO( 'mysql:dbname=heroku_921e62bfdd3a5fd;host=us-cdbr-east-04.cleardb.com;charset=utf8', 'bf76585b664e5b', 'e925f993' );
					} catch ( PDOException $error ) {
						//エラーの場合はエラーメッセージを吐き出す
						exit( "データベースに接続できませんでした。<br>" . $error->getMessage() );
					}

					//prepareでSQLを準備
					//					$id = 34;
					//					echo $id;
					$select = $pdo->prepare( "SELECT * FROM property LIMIT 5 OFFSET 5" );
					$select->execute(); //準備したSQLを実行。PDO Statementクラスのオブジェクトが渡される。(SQLの結果も保持する)

					//PDO Statementのfetchメッソドを利用して、結果を一行ずつ取得。データがなくなるとFALSEを返す。
					while ( $row = $select->fetch( PDO::FETCH_ASSOC ) ) {
						$title = $row[ 'property_name' ]; //タイトルを取得
						$file_path = $row[ 'id' ]; //ファイルパスを取得
						$station = $row[ 'station' ];
						$zip = $row[ 'zip' ];
						$state = $row[ 'state' ];
						$city = $row[ 'city' ];
						$street = $row[ 'street' ];
						$email = $row[ 'email' ];
						$phone = $row[ 'phone' ];
						$description = $row[ 'description' ];
						$date = $row[ 'post_date' ];

						echo "<div class='list'> <a href='./property.php?id=$file_path'>
						<figure><img src='images/iniad.jpg' alt=''></figure>
						<h4>$title<span class='newicon'>NEW</span></h4>
						<p>所在地：$state $city<br>
							価格：XXXXX円</p>
						</a> </div>";

					}
					?>
				</div>
				<div class="box1">
					<h2>アクセス</h2>
					<iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3236.799789567791!2d139.71335761526143!3d35.7802940801713!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6018931934050759%3A0x239ff01917c6ae5a!2z5p2x5rSL5aSn5a2mIOaDheWgsemAo-aQuuWtpumDqCAoSU5JQUQp!5e0!3m2!1sja!2sjp!4v1633841676994!5m2!1sja!2sjp"
              width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
					<p> 〒115-8650 <br>
						東京都北区赤羽台１丁目７−１１<br>
						TEL：03-5924-2100<br>
						受付：9:00～16:00<br>
						定休日：土日祝</p>
				</div>
			</div>
			<!--/#sub--> 
			
		</div>
		<!--/#contents-in--> 
		
	</div>
	<!--/#contents--> 
	
</div>
<!--/#container-->

<footer>
	<div id="footermenu">
		<ul>
			<li><a href="update.php">更新</a></li>
		</ul>
		<ul>
			<li><a href="process.php">過程</a></li>
		</ul>
		<ul>
			<li><a href="member.php">メンバー</a></li>
		</ul>
		<ul>
			<li><a href="link.php">参考</a></li>
		</ul>
		<ul>
			<li><a href="list.php">物件一覧</a></li>
		</ul>
	</div>
	<!--/footermenu-->
	
	<div id="copyright"> <small>Copyright&copy; <a href="index.php">Vacant inc.</a> All Rights Reserved.</small> <span class="pr"><a href="http://template-party.com/" target="_blank">《Web Design:Template-Party》</a></span> </div>
</footer>
<p class="nav-fix-pos-pagetop"><a href="#">↑</a></p>

<!--小さな端末用（800px以下端末）メニュー--> 
<script type="text/javascript">
    if (OCwindowWidth() <= 800) {
      open_close("newinfo_hdr", "newinfo");
    }
  </script> 

<!--メニュー開閉ボタン-->
<div id="menubar_hdr" class="close"></div>
<!--メニューの開閉処理条件設定　800px以下--> 
<script type="text/javascript">
    if (OCwindowWidth() <= 800) {
      open_close("menubar_hdr", "menubar-s");
    }
  </script>
</body>
</html>