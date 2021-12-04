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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script type="text/javascript" src="js/openclose.js"></script> 
<script type="text/javascript" src="js/fixmenu_pagetop.js"></script> 
<script type="text/javascript" src="js/myscript.js"></script> <!-- Bootstrap CSS --> 
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
			<a href="form.php"><img class="icon" id="formimg" src="./images/form.png" alt=""></a> <img class="icon" id="searchimg" src="./images/search.png" alt=""> </div>
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
	<div id="contents">
		<div id="contents-in">
			<div id="main">
				<section>
					<div class="l-section guide-styles">
						<h2>空きスペースをお持ちのオーナー様へ</h2>
						<p class="description">一般的な『レンタルオフィス』との違いやメリットをご紹介！</p>
						<ul id="top_feature">
							<!--
	-->
							<li>
								<dl>
									<dt>Vacantの特徴</dt>
									<dd>
										<p> Vacantはコロナ禍により入居者が減ってしまった等の理由で空き部屋を抱えているオーナー様や、別荘などの使っていないお部屋をお持ちの方と、安価にレンタルオフィスを利用したいユーザーを繋げるサービスです。<br>
											総務省「住宅・土地統計調査」によると、全国の空き家数は、過去30年（1988年～2018年）で394万戸から849万戸となり、２倍以上増加しています。空き家率（空き家戸数が総住宅戸数に占める割合）も上昇を続けており、2018年の空き家率は13.6%に達しています。<br>
											そんな中、既存の空きスペースを有効活用できるようにと生まれたのがVacantです。 </p>
									</dd>
								</dl>
							</li>
							<!--
	-->
							<li>
								<dl>
									<dt>Vacantの利用シーン</dt>
									<dd>
										<p> コロナ禍でテナントを募集していたり、入居者が現れず物件を余らせているオーナー様、ぜひVacantを利用してみませんか？<br>
											Vacantは、会社がテレワークになったけれど自宅に環境がない方や出張先で仕事ができるスペースを安価に探している方、グループワークに利用できるスペースを探している方など、『空きスペースを収益化したい方』と『安価にオフィスを探している方』にとってマッチするサービスになっています。 </p>
									</dd>
								</dl>
							</li>
							<!--
	-->
							<li>
								<dl>
									<dt>Vacantを使うメリット</dt>
									<dd>
										<p> Vacantを利用するメリットは初期費用がかからないことにあります。通常、レンタルオフィスとして部屋を貸し出す場合はスペースに多額の投資をし、必要な設備を整えた上で部屋の装飾を施すことでデザインされた空間を提供することが多いです。<br>
											しかし、Vacantの場合は部屋の装飾をする必要はありません。ユーザーに対して安価に提供するためにも、部屋自体のデザインよりも必要最低限の設備を用意することをメインに考えています。必要最低限の設備とは『Wi-Fi』『電源』『トイレ』と定義しました。この基準を満たしている場合は物件登録フォームよりお申し込みください。<br>
											また、立地も問いません。通常レンタルオフィスは一等地に建っていることが多いですが、自宅の近くに借りたいユーザーもいると考え、立地を問わずお申し込みいただけるサービスになっております。 </p>
									</dd>
								</dl>
							</li>
							<!--
	-->
						</ul>
					</div>
				</section>
				<br>
				<section>
					<h2>物件登録フォーム</h2>
					
					<!-- フォームの情報はここからです -->
					<div class="l-section">
					<form name="form1" enctype="multipart/form-data" method="post" action="register.php">
						<input type="hidden" name="mode" value="form">
						<table class="ta1">
							<!-- 入力／確認 --> 
							<!--<tr><th class="tamidashi" colspan="2">物件一覧フォーム</th></tr>-->
							<tr>
								<th width="150">申込者名<span style="color:red">※</span></th>
								<td><input name="applicant_name" id="applicant_name" value="" type="text" size="40" maxlength="500" class="ws" placeholder="東洋太郎"></td>
							</tr>
							<tr>
								<th width="150">物件名<span style="color:red">※</span></th>
								<td><input name="property_name" id="property_name" value="" type="text" size="40" maxlength="500" class="ws" placeholder="サンプルビル"></td>
							</tr>
							<tr>
								<th width="150">最寄駅<span style="color:red">※</span></th>
								<td><input name="station" id="station" value="" type="text" size="40" maxlength="500" class="ws" placeholder="赤羽駅"></td>
							</tr>
							<tr>
								<th class="form_table_th"><label for="zip" class="form_obj">住所<span style="color:red">※</span></label></th>
								<td class="form_table_td "><span class="form_obj">
									<table cellspacing="0">
										<tbody>
											<tr>
												<th style="width: 100px"><label for="zip">郵便番号</label></th>
												<td><input id="zip" name="zip" type="number" maxlength="20" class="p-postal-code" placeholder="1080074（ハイフン無し）"></td>
											</tr>
											<tr class="spt10">
												<th><label for="state">都道府県</label></th>
												<td><select id="state" name="state" class="p-region">
														<option value="">-- 選択してください --</option>
														<option value="北海道">北海道</option>
														<option value="青森県">青森県</option>
														<option value="岩手県">岩手県</option>
														<option value="宮城県">宮城県</option>
														<option value="秋田県">秋田県</option>
														<option value="山形県">山形県</option>
														<option value="福島県">福島県</option>
														<option value="茨城県">茨城県</option>
														<option value="栃木県">栃木県</option>
														<option value="群馬県">群馬県</option>
														<option value="埼玉県">埼玉県</option>
														<option value="千葉県">千葉県</option>
														<option value="東京都">東京都</option>
														<option value="神奈川県">神奈川県</option>
														<option value="新潟県">新潟県</option>
														<option value="富山県">富山県</option>
														<option value="石川県">石川県</option>
														<option value="福井県">福井県</option>
														<option value="山梨県">山梨県</option>
														<option value="長野県">長野県</option>
														<option value="岐阜県">岐阜県</option>
														<option value="静岡県">静岡県</option>
														<option value="愛知県">愛知県</option>
														<option value="三重県">三重県</option>
														<option value="滋賀県">滋賀県</option>
														<option value="京都府">京都府</option>
														<option value="大阪府">大阪府</option>
														<option value="兵庫県">兵庫県</option>
														<option value="奈良県">奈良県</option>
														<option value="和歌山県">和歌山県</option>
														<option value="鳥取県">鳥取県</option>
														<option value="島根県">島根県</option>
														<option value="岡山県">岡山県</option>
														<option value="広島県">広島県</option>
														<option value="山口県">山口県</option>
														<option value="徳島県">徳島県</option>
														<option value="香川県">香川県</option>
														<option value="愛媛県">愛媛県</option>
														<option value="高知県">高知県</option>
														<option value="福岡県">福岡県</option>
														<option value="佐賀県">佐賀県</option>
														<option value="長崎県">長崎県</option>
														<option value="熊本県">熊本県</option>
														<option value="大分県">大分県</option>
														<option value="宮崎県">宮崎県</option>
														<option value="鹿児島県">鹿児島県</option>
														<option value="沖縄県">沖縄県</option>
													</select></td>
											</tr>
											<tr class="spt10">
												<th><label for="city">市区郡</label></th>
												<td><input id="city" name="city" type="text" maxlength="40" class="p-locality" placeholder="港区"></td>
											</tr>
											<tr class="spt10">
												<th><label for="street">町名 番地 建物名</label></th>
												<td><input id="street" name="street" type="text" maxlength="100" class="p-street-address"
                                                    placeholder="赤羽台１丁目７−１１"></td>
											</tr>
										</tbody>
									</table>
									</span></td>
							</tr>
							<tr>
								<th width="150">メールアドレス<span style="color:red">※</span></th>
								<td><input name="email" id="email" value="sample@iniad.org" type="email" size="40" maxlength="500" class="ws" placeholder="sample@iniad.org"></td>
							</tr>
							<tr>
								<th width="150">電話番号<span style="color:red">※</span></th>
								<td><input name="phone" id="phone" value="08011112222" type="tel" size="40" maxlength="500" class="ws" placeholder="08011112222（ハイフン無し）"></td>
							</tr>
							<tr>
								<th>備考<span style="color:red">※</span></th>
								<td><textarea name="description" id="description" cols="40" rows="10" class="wl"></textarea></td>
							</tr>
						</table>
						</div>
						<div class="c">
							<input type="reset" value="リセット" class="btn">
							&nbsp;
							<input type="submit" value="送信する" class="input_btn">
						</div>
						<div id="sendbtn" class="btn" style=""> </div>
						<br>
					</form>
					</tbody>
					<!-- フォームの情報はここまでです --> 
					
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
<!--メニューの開閉処理条件設定 800px以下--> 
<script type="text/javascript">
if (OCwindowWidth() <= 800) {
	open_close("menubar_hdr", "menubar-s");
}
</script>
</body>
</html>
