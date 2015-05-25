<?php
class SC extends IOC implements checkin {
        public function SC() {
                parent ::  __construct($parameter);
        }
	/** @Service */
	// 山航值机入口
	public function Entrance() {
		$this->addResult(__LINE__, "值机成功", "山航值机入口", "Information");
		$orgList = 'HRB:哈尔滨|WNZ:温州|DLC:大连|WUH:武汉|CKG:重庆|CGQ:长春|TAO:青岛|SJW:石家庄|CAN:广州|KWL:桂林|BHY:北海|LYA:洛阳|BAV:包头|XIY:西安|HAK:海口|NNG:南宁|MIG:绵阳|XNN:西宁|SZX:深圳|ZUH:珠海|JIU:九江|JNG:济宁|INC:银川|HGH:杭州|NKG:南京|WUS:武夷山|WEH:威海|SHE:沈阳|JDZ:景德镇|XMN:厦门|HFE:合肥|YNT:烟台|FOC:福州|CSX:长沙|CGO:郑州|LYI:临沂|HLD:海拉尔|NGB:宁波|HET:呼和浩特|KHN:南昌|TSN:天津|PVG:上海浦东|HSN:舟山|LHW:兰州|SWA:揭阳（汕头）|TPE:台北|PEK:北京|KWE:贵阳|DSN:鄂尔多斯|TYN:太原|TNA:济南|CTU:成都|SHA:上海虹桥|SYX:三亚';
		$this->addForm(
						array(
								new Text("证件号码", "idno", ""),
								new Select("证件类型", "idtype", "NI:身份证|PP:护照|TN:票号"),
								new Text("旅客姓名", "passname", "", "国际票请按姓(拼音)/名(拼音)格式输入"),
								new Text("手机号码", "phone", ""),
								new Hidden("orgId", "SCAIR"),
								new Hidden("language", "cn"),
								new Select("始发城市", "org", $orgList),
								new Hidden("city", ""),
								new Radio("operate", "PA:值机", "PW:取消值机")
							)
						);
		$this->addNext(Next :: getInstance(array("FlightList")));
	}
	
	/** @Service */
	// 获取航段信息
	public function FlightList() {
		$info = "山航航段列表(" . __METHOD__ . ") ";

		if ( $this->param->getPost('operate') == 'PW' )
			$url = 'http://webcheckin.travelsky.com/webcki/CussQueryFront.do';
		else
			$url = 'http://sc.travelsky.com/scet/checkinindex.do';
		$header = [
			'Referer' => 'http://www.shandongair.com.cn/',
			'Accept-Language' => 'zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3',
		];
		// 处理接收的post数据
		$posts = $this->param->getPost();
//		$passname = urlencode(StringUtils :: convert2GBK($this->param->getPost('passname')));
//		$posts = str_replace(urlencode($this->param->getPost('passname')), $passname, $posts);
				
		try{
			$request = Requests::post($url, $header, $posts);
		}catch (Requests_Exception $e){
			$this->addResult(__LINE__, "值机失败", "网关错误" . $e->getMessage() . '[' . $e->getCode() . ']', ALTER);
			$this->error($info . __LINE__, $this->getResult());
			return;
		}
		$result = $request->body;
		$cookie = $request->cookies->getIterator();
		$cookie = json_decode(json_encode($cookie),true);
		foreach($cookie as $key=>$value){
			$cookie[$key]=$key.'='.$value['value'];
			// $arraycookie[$key]=$value['value'];
		}
		$cookie = implode(';', $cookie);

		file_put_contents('FlightList_request.html', serialize($request));
		// 过滤错误信息
		if ( $result == '' ) {
			$this->addResult(__LINE__, "值机失败", '网络请求超时', ALTER);
			$this->error($info . __LINE__ , $this->getResult());
			return;
		}
		$result = StringUtils :: convert2utf8('GBK', $result);
		if ( StringUtils :: index($result, '本次航班暂停提供网上值机业务') != -1 ) {
			$this->addResult(__LINE__, "值机失败", '本次航班暂停提供网上值机业务', ALTER);
            $this->error($info . __LINE__ , $this->getResult());
            return;
		}
		if ( StringUtils :: index($result, '没有找到您的网上值机记录') != -1 ) {
			$this->addResult(__LINE__, "值机失败", '没有找到您的网上值机记录', ALTER);
            $this->error($info . __LINE__ , $this->getResult());
            return;
		}
		// 获取cookie
//		print_r($request->cookies);
//		file_put_contents('FlightList_cookie.txt', json_encode($request->cookies));
//		file_put_contents('FlightList_header.txt', $request->headers);
		//simple html dom 获取DOM信息
		file_put_contents('FlightList_body.html', $result);
		$html = str_get_html($result);
		//取消值机
		if ( $this->param->getPost('operate') == 'PW' ) {
			if ( StringUtils :: index($result, '<table width="929" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#9ac5e3">') == -1 ) {
				$this->addResult(__LINE__, "值机失败", '无法提取到您的行程信息', ALTER);
            	$this->error($info . __LINE__ , $this->getResult());
            	return;
			}
			$fls = explode('<table width="929" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#9ac5e3">', $result);
			$fls = $fls[1];
			$fls = explode('</table>', $fls);
			$fls = $fls[0];
			$flightList = new FlightList();
			$flightList->add("编号 出发日期 航班号 始发站 到达站 仓位 票号 状态 操作");
			$tr = explode('</tr>', $fls);
			array_pop($tr);
			array_shift($tr);
			foreach($tr as $k => $v) {
				$recno = StringUtils :: match($v, '/name="recno"  value="(.+?)"\/>/');
				$recno = explode(',', $recno);
				$recno = $recno[0];
				$flightList->add(new Hidden("recno", $recno));
				$flightList->add(new Hidden("orgid", "SCAIR"));
				$flightList->add(new FlightCell("", $k));
				$va = explode('</td>', $v);
				$va[4] = explode('>', $va[4]);
				$va[4] = $va[4][1];
				$flightList->add(new FlightCell("", $va[4]));
				
				$va[2] = explode('>', $va[2]);
				$va[2] = $va[2][1];
				$flightList->add(new FlightCell("", $va[2]));
				
				$va[5] = explode('>', $va[5]);
				$va[5] = $va[5][1];
				$va[5] = explode('-', $va[5]);
				$flightList->add(new FlightCell("", $va[5][0]));
				$flightList->add(new FlightCell("", $va[5][1]));
				
				$va[3] = explode('>', $va[3]);
				$va[3] = $va[3][1];
				$flightList->add(new FlightCell("", $va[3]));
				
				$va[7] = explode('>', $va[7]);
				$va[7] = $va[7][1];
				$flightList->add(new FlightCell("", $va[7]));
				
				$va[11] = explode('>', $va[11]);
				$va[11] = $va[11][1];
				$flightList->add(new FlightCell("", $va[11]));
				$flightList->add(new Button("取消值机", "CancelCheckin"));
			}
			$this->addResult(__LINE__, "值机成功", "航段列表", FLIGHTLIST);
	        // 记录航段信息
	        $this->addForm(array($flightList));
	        $this->addNext(Next::getInstance(array("CancelCheckin"), $cookie));
			return;
		}
//		if ( StringUtils :: index($result, '<table width="929" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#9ac5e3" class="Margin-top20">') == -1 ) {
//			$this->addResult(__LINE__, "值机失败", '无法提取到您的行程信息', ALTER);
//            $this->error($info . __LINE__ , $this->getResult());
//            return;
//		}
		
//		$fls = explode('<table width="929" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#9ac5e3" class="Margin-top20">', $result);
		// 获取乘客信息
		$pass_info_table = $html->find('table',0);
		// 获取航班信息
		$flight_list_table = $html->find('table',1);
		file_put_contents('pass_info.html',$pass_info_table);
		file_put_contents('flight_list.html',$flight_list_table);

//		$fls[0] = explode('</table>', $fls[1]);
//		$fls[1] = explode('</table>', $fls[2]);
//		$fls[0] = $fls[0][0];
//		$fls[1] = $fls[1][0];
//
//		$tr = explode('</tr>', $fls[1]);
//		array_pop($tr);
//		array_shift($tr);
//		array_shift($tr);

//		$idType = StringUtils :: match($result, '/name="idType" value="(.+?)"/');
//		$idNo = StringUtils :: match($result, '/name="idNo" value="(.+?)"/');
//		$passengerName = StringUtils :: match($result, '/name="passengerName" value="(.+?)"/');
//		foreach($pass_info->find('tr td') as $element){
//			$pass_info_arr[] = $element->plaintext;
//		}
//		file_put_contents('pass_info_arr.txt',implode('&',$pass_info_arr));
		$flightList = new FlightList();
		// 获取乘客基本信息
		$passengerName 	= $pass_info_table->find('tr td',0)->plaintext;
		$tktNo 			= $pass_info_table->find('tr td',1)->plaintext;
		$idType 		= $pass_info_table->find('tr td',2)->plaintext;
		$idNo 			= $pass_info_table->find('tr td',3)->plaintext;
//		$passType 		= $pass_info_table->find('tr td',4) == '成人'? 0 : 1;

		//航班信息
		$flightList->add("编号 航班日期 航班号 出发站 到达站 仓位 票号 状态 操作");
		$table_tr_arr = $flight_list_table->find('tr');
		array_shift($table_tr_arr);
		foreach( $table_tr_arr as $key => $tr_arr) {
			$Segment 		= $tr_arr->find('td',0)->plaintext; //航段
			$fnum 			= $tr_arr->find('td',1)->plaintext; //航班号
			$cabin 			= $tr_arr->find('td',2)->plaintext; //舱位
			$fdate 			= $tr_arr->find('td',3)->plaintext; //航班时间
			$pnr 			= $tr_arr->find('td',4)->plaintext; //订座记录编码
			$segSelectPage 	= $tr_arr->find('td input',0)->value; //选择
			$Segment = explode('-',$Segment);
			$org = trim($Segment[0]);
			$dst = trim($Segment[1]);
			$segSelect_arr = explode(',', $segSelectPage);
			$passType = $segSelect_arr[3];
			$segSelect = $segSelect_arr[0];
			$flightList->add(new Hidden("segSelectPage", $segSelectPage));
			$flightList->add(new FlightCell("编号", '0'));
			$flightList->add(new FlightCell("date", $fdate));
			$flightList->add(new FlightCell("fnum", $fnum));
			$flightList->add(new FlightCell("org", $org));
			$flightList->add(new FlightCell("dst", $dst));
			$flightList->add(new FlightCell("cabin", $cabin));
			$flightList->add(new FlightCell("tktNo", $tktNo));
			$flightList->add(new FlightCell("", "值机"));
			$flightList->add(new Button("值机", "SelectSeat"));
			$flightList->add(new Hidden("pnr", $pnr));
//			$passType = $v[5][3];
//			$segSelect = $v[5][0];
			$flightList->add(new Hidden("idType", $idType));
			$flightList->add(new Hidden("idNo", $idNo));
			$flightList->add(new Hidden("passengerName", $passengerName));
			$flightList->add(new Hidden("passType", $passType));
			$flightList->add(new Hidden("segSelect", $segSelect));
			$flightList->add(new Hidden("phone", $this->param->getPost('phone')));
			$flightList->move();
		}
//		file_put_contents('flight_data.txt', $pnr);
		file_put_contents('flight_data.txt', $flightList->label);
		file_put_contents('flight_data.txt',json_encode($flightList->field[0]), FILE_APPEND );
		if ( $flightList->size() == 0 ) {
            $this->addResult(__LINE__, "值机失败", "无可用航段", ALTER);
            $this->error($info . __LINE__, $this->getResult());
            return;
        }
        $this->addResult(__LINE__, "值机成功", "航段列表", FLIGHTLIST);
        // 记录航段信息
		$this->addForm(array($flightList));
		$this->addNext(Next::getInstance(array("SelectSeat"), $cookie));
	}
	public function SelectSeat() {
		$info = '山航座位图(' . __METHOD__ . ') ';
		$url = 'http://sc.travelsky.com/scet/checkinconfirm.do';
		$cookie = $this->param->getHeader();
		// 判断是否能值机
		$header = array(
						"Cookie"	=> $cookie,
						"Referer"	=> 'http://sc.travelsky.com/scet/checkinindex.do'
						);
		$posts = $this->param->getPost();
//		var_dump($posts);

		try{
			$request = Requests::post($url, $header, $posts);
		}catch (Requests_Exception $e){
			$this->addResult(__LINE__, "值机失败", "网关错误" . $e->getMessage() . '[' . $e->getCode() . ']', ALTER);
			$this->error($info . __LINE__, $this->getResult());
			return;
		}
		$result = $request->body;

		file_put_contents('confirm_request.html', serialize($request));
		//过滤错误信息
		if ( $result == '' ) {
			$this->addResult(__LINE__, "值机失败", '网络请求超时', ALTER);
            $this->error($info . __LINE__ , $this->getResult());
            return;
		}
		$result = StringUtils :: convert2utf8('gbk', $result);
		if ( StringUtils :: index($result, '本次航班暂停提供网上值机业务') != -1 ) {
			$this->addResult(__LINE__, "值机失败", '本次航班暂停提供网上值机业务，请前往机场柜台办理', ALTER);
            $this->error($info . __LINE__ , $this->getResult());
            return;
		}
		//提取航客确认信息
		$html = str_get_html($result);
		foreach ($html->find('input') as $element) {
			$Cuss_post[$element->name]=$element->value;
		}
		$Cuss_post['confirmrule']='on';
		$Cuss_post['tktno'] = StringUtils :: match($result, '/name="tktno"value="(.+?)"/');
		$Cuss_post['passname']=urlencode(iconv('UTF-8', 'GBK',$Cuss_post['passname']));
		$Cuss_post['Submit']=urlencode(iconv('UTF-8', 'GBK',$Cuss_post['Submit']));
		$Cuss_post['remarkurl']=urlencode($Cuss_post['remarkurl']);
		//组合post
		foreach ($Cuss_post as $key => $value) {
			$cuss_post_str[]=$key.'='.$value;
		}
		$posts = implode("&", $cuss_post_str);

		$header['Referer'] = $url;
		$url = 'http://webcheckin.travelsky.com/webcki/CussPre.do';
		$cookie = '';
		$this->post($url, $posts, $header);
		$result = $this->httpClient->getBody();
		$cookie = $this->httpClient->getHeader('cookie');
		if ( $result == '' ) {
			$this->addResult(__LINE__, "值机失败", '网络请求超时', ALTER);
            $this->error($info . __LINE__ , $this->getResult());
            return;
		}
//		file_put_contents('webcki_body.html', $result);
		//提取seat 表格信息
		$cuss_html = str_get_html($result);
		$seat_table = $cuss_html->find('table[align=center]',0);
		if ( !isset($seat_table) ) {
			$this->addResult(__LINE__, "值机失败", '未查到座位图', ALTER);
            $this->error($info . __LINE__ , $this->getResult());
            return;
		}

		//处理seat 表格
		foreach ($seat_table->find('input') as $input_element) {
			switch ($input_element->name) {
				case 'button':
					$input_element->outertext = '.';
					break;
				case 'notavailable':
					$input_element->outertext = '4';
					break;
				case 'available':
					$input_element->outertext = '*';
					break;

				default:
					$input_element->outertext = '';
					break;
			}
		}
		$seat_table = str_get_html($seat_table);
		foreach ($seat_table->find('tr') as $tr_arr) {
			foreach ($tr_arr->find('td') as $td) {
					if(empty(trim($td->plaintext))){
						$temp[] = ' ';
						continue;
					}
					$temp[] = trim($td->plaintext);
			}
			array_pop($temp);
			$seat[] = implode(' ', $temp);
			unset($temp);
		}
		array_pop($seat);
		array_pop($seat);
		$seat = implode("\r\n", $seat);

		file_put_contents('webcki_seat_map.txt',$seat);
		$cabin = StringUtils :: match($result, '/name=basecabin value="(.+?)"/');
        $this->addResult(__LINE__, "值机成功", "座位图", SEATMAP);
        $this->addForm(array(
            			new Hidden("basecabin", $cabin),
            			new Hidden("orgid", 'SCAIR'),
            			new Hidden("number", "on"),
            			new Hidden("ffpairline", ""),
            			new Hidden("ffpnum", ""),
            			new Hidden("mobileno", $this->param->getPost('phone')),
            			new SeatMap("seatno", $seat),
            			new Hidden("seatno2",""),
            			new Hidden("agreeall", "on")
                        )
                );
        $this->addNext(Next::getInstance(array("Checkin"), $this->param->getHeader().'; ' . $cookie));
	}
	public function Checkin() {
		$info = '山航值机(' . __METHOD__ . ') ';
		$url = 'http://webcheckin.travelsky.com/webcki/Cuss.do';
		$header = array(
						"Cookie"	=> $this->param->getHeader(),
						"Referer"	=> 'http://webcheckin.travelsky.com/webcki/CussPre.do'
						);
		$this->post($url, $this->param->getPost(), $header);
		$result = $this->httpClient->getBody();
		if ($result == '') {
			$this->addResult(__LINE__, "值机失败", '请求超时,若收到短信则值机成功', ALTER);
			$this->error($info . __LINE__, $this->getResult());
			return;
		}
		$result = StringUtils :: convert2utf8('GBK', $result);
		if ( StringUtils :: index($result, '值机失败') != -1 ) {
			$this->addResult(__LINE__, "值机失败", "可能已经值机过了", ALTER);
			$this->error($info . __LINE__, $this->getResult());
			return;
		}
		$this->addResult(__LINE__, "值机成功", "", ALTER);
		
		// 拉取登机牌
		$recno = StringUtils :: match($result, '/id="cuss_style" value="([0-9]+),/');
		
		$header['Referer'] = 'http://webcheckin.travelsky.com/webcki/Cuss.do';
		$url = 'http://webcheckin.travelsky.com/webcki/CussPrint.do';
		//$this->post($url, 'recno=' . $recno, $header);
	}
	public function CancelCheckin(){
		$info = '山航取消值机(' . __METHOD__ . ') ';
		$verifyno = $this->param->getPost('verifyno');
		$header = array();
		$header['Cookie'] = $this->param->getHeader();
		if ( $verifyno == NULL || $verifyno == '' ) {
			$url = 'http://webcheckin.travelsky.com/webcki/ToVerify.do';			
			$header['Referer'] = 'http://webcheckin.travelsky.com/webcki/CussQueryFront.do';
			$this->post($url, $this->param->getPost(), $header);
			$result = $this->httpClient->getBody();
			$result = StringUtils :: convert2utf8('GBK', $result);
			$header['Referer'] = $url;
			$url = 'http://webcheckin.travelsky.com/webcki/SendVerify.do';
			$this->post($url, $this->param->getPost() . '&pwid=', $header);
			$this->addResult(__LINE__, "值机成功", "取消值机验证码", FLIGHTLIST);
	        $this->addForm(array(
								new Code("验证码", new Uri("手机", "verifyno", "phone://100000")),
								new Hidden("recno", $this->param->getPost('recno')),
								new Hidden("orgid", $this->param->getPost('orgid')),
								new Hidden("pwid", "")
								)
							);
	        $this->addNext(Next::getInstance(array("SelectSeat"), $this->param->getHeader()));
	        return;
		} else {
			$url = 'http://webcheckin.travelsky.com/webcki/Pw.do';
			$header['Referer'] = 'http://webcheckin.travelsky.com/webcki/ToVerify.do';
			$this->post($url, $this->param->getPost(), $header);
			$result = $this->httpClient->getBody();
			$result = StringUtils :: convert2utf8('GBK', $result);
			if ( StringUtils :: index($result, '取消自助值机成功') == -1 )
				$this->addResult(__LINE__, "值机失败", "验证码错误或重复取消,最终以短信为主", ALTER);
			else
				$this->addResult(__LINE__, "值机成功", "取消成功,最终以短信为主", ALTER);
		}
	}
}
