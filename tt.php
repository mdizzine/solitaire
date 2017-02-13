<?php
  class Card{
	public $num = 0;
	public $face = '';
	public $pos = 0;
  }
  
  $deck = array();
  $col1 = array();
  $col2 = array();
  $col3 = array();
  $col4 = array();
  $col5 = array();
  $col6 = array();
  $col7 = array();
  $foundation1 = array();
  $foundation2 = array();
  $foundation3 = array();
  $foundation4 = array();
  $draw = array();
  $discard =  array();
  $drawEmpty = false;
  
  function maxPileAmount(){
	$max = count($GLOBALS['col1']);
	if(count($GLOBALS['col2']) > $max){
		$max = count($GLOBALS['col2']);
	}
	if(count($GLOBALS['col3']) > $max){
		$max = count($GLOBALS['col3']);
	}
	if(count($GLOBALS['col4']) > $max){
		$max = count($GLOBALS['col4']);
	}
	if(count($GLOBALS['col5']) > $max){
		$max = count($GLOBALS['col5']);
	}
	if(count($GLOBALS['col6']) > $max){
		$max = count($GLOBALS['col6']);
	}
	if(count($GLOBALS['col7']) > $max){
		$max = count($GLOBALS['col7']);
	}
	if(count($GLOBALS['foundation1']) > $max){
		$max = count($GLOBALS['foundation1']);
	}
	if(count($GLOBALS['foundation2']) > $max){
		$max = count($GLOBALS['foundation2']);
	}
	if(count($GLOBALS['foundation3']) > $max){
		$max = count($GLOBALS['foundation3']);
	}
	if(count($GLOBALS['foundation4']) > $max){
		$max = count($GLOBALS['foundation4']);
	}
	return $max;
  }
  
  function printCard($ray, $count){
	if(count($ray) > $count){
		if($ray[$count]->pos==1){
			echo $ray[$count]->face . ' ';
		}else{
			echo '__' . ' ';
		}
	}else{
		echo '   ';
	}
  }
  
  function printTable(){
	echo PHP_EOL;
	echo 'C1 C2 C3 C4 C5 C6 C7 F1 F2 F3 F4' . PHP_EOL;
	echo '~~ ~~ ~~ ~~ ~~ ~~ ~~ ~~ ~~ ~~ ~~' . PHP_EOL;
	$max = maxPileAmount();
	$cnt = 0;
	while($cnt < $max){
		printCard($GLOBALS['col1'],$cnt);
		printCard($GLOBALS['col2'],$cnt);
		printCard($GLOBALS['col3'],$cnt);
		printCard($GLOBALS['col4'],$cnt);
		printCard($GLOBALS['col5'],$cnt);
		printCard($GLOBALS['col6'],$cnt);
		printCard($GLOBALS['col7'],$cnt);
		printCard($GLOBALS['foundation1'],$cnt);
		printCard($GLOBALS['foundation2'],$cnt);
		printCard($GLOBALS['foundation3'],$cnt);
		printCard($GLOBALS['foundation4'],$cnt);
		echo PHP_EOL;
		$cnt++;
	}
	echo '__' . PHP_EOL;
	if(count($GLOBALS['draw'])>0){
		echo $GLOBALS['draw'][0]->face;
	}
	echo PHP_EOL . PHP_EOL . '$>';
  }
  
  function unreadable(){
	echo PHP_EOL . 'Unintelligible query' . PHP_EOL . 'Enter to continue' . PHP_EOL;
	stream_get_line(STDIN,1024,PHP_EOL);
	printTable();
  }
  
  function drawCard(){
	if(!$GLOBALS['drawEmpty']){
		if(count($GLOBALS['draw'])>0){
			array_push($GLOBALS['discard'],$GLOBALS['draw'][0]);
		}
		array_splice($GLOBALS['draw'], 0, 1);
		if(count($GLOBALS['draw'])>0){
			$GLOBALS['draw'][0]->pos = 1;
		}elseif(count($GLOBALS['discard'])==0){
			$GLOBALS['drawEmpty'] = true;
			echo 'There are no more cards in the draw or discard piles.' . PHP_EOL . 'Enter to continue' . PHP_EOL;
			stream_get_line(STDIN,1024,PHP_EOL);
		}else{
			$GLOBALS['draw'] = $GLOBALS['discard'];
			$GLOBALS['discard'] = array();
		}
	}else{
		echo 'There are no more cards in the draw or discard piles.' . PHP_EOL . 'Enter to continue' . PHP_EOL;
		stream_get_line(STDIN,1024,PHP_EOL);
	}
  }
  
  for($i = 0; $i < 52; $i++){
	$suit = (int)($i/13);
	$number = ($i%13) + 1;
	switch($number){
		case 1:
			$number = 'A';
			break;
		case 10:
			$number = 'X';
			break;
		case 11:
			$number = 'J';
			break;
		case 12:
			$number = 'Q';
			break;
		case 13:
			$number = 'K';
			break;
	}
	switch($suit){
		case 0:
			$suit = 'S';
			break;
		case 1:
			$suit = 'H';
			break;
		case 2:
			$suit = 'C';
			break;
		case 3:
			$suit = 'D';
			break;
	}
	$card = new Card;
	$card->face = $number . $suit;
	$card->num = $i;
	array_push($deck, $card);
  }
  shuffle($deck);
  $inc = 1;
  foreach($deck as $card){
	if($inc>28){
		$card->pos = 1;
		array_push($draw,$card);
	}elseif($inc>21){
		if(count($col7)==6){
			$card->pos = 1;
		}
		array_push($col7,$card);
	}elseif($inc>15){
		if(count($col6)==5){
			$card->pos = 1;
		}
		array_push($col6,$card);
	}elseif($inc>10){
		if(count($col5)==4){
			$card->pos = 1;
		}
		array_push($col5,$card);
	}elseif($inc>6){
		if(count($col4)==3){
			$card->pos = 1;
		}
		array_push($col4,$card);
	}elseif($inc>3){
		if(count($col3)==2){
			$card->pos = 1;
		}
		array_push($col3,$card);
	}elseif($inc>1){
		if(count($col2)==1){
			$card->pos = 1;
		}
		array_push($col2,$card);
	}else{
		if(count($col1)==0){
			$card->pos = 1;
		}
		array_push($col1,$card);
	}
	$inc++;
  }
  
  printTable();
  $end = false;
  while(!$end){
	$input = stream_get_line(STDIN,1024,PHP_EOL);
	$input = trim(strtolower($input));
	$splitter = explode(' to ',$input);
	foreach($splitter as $s){
		$s = trim($s);
	}
	switch(count($splitter)){
		case 1:
			switch($splitter[0]){
				case 'draw':
				case 'd':
					drawCard();
					printTable();
					break;
				case 'quit':
				case 'exit':
				case 'q':
					$end = true;
					break;
				default:
					unreadable();
					break;
			}
			break;
		case 2:
			$origin = $splitter[0];
			$destination = $splitter[1];
			$originType = str_split($origin)[0];
			$destinationType = str_split($destination)[0];
			switch($origin){
				case 'draw':
				case 'd':
					$origin = $draw;
					break;
				case 'c1':
					$origin = $col1;
					break;
				case 'c2':
					$origin = $col2;
					break;
				case 'c3':
					$origin = $col3;
					break;
				case 'c4':
					$origin = $col4;
					break;
				case 'c5':
					$origin = $col5;
					break;
				case 'c6':
					$origin = $col6;
					break;
				case 'c7':
					$origin = $col7;
					break;
				case 'f1':
					$origin = $foundation1;
					break;
				case 'f2':
					$origin = $foundation2;
					break;
				case 'f3':
					$origin = $foundation3;
					break;
				case 'f4':
					$origin = $foundation4;
					break;
				default:
					$origin = 'error';
					break;
			}
			if($origin!='error'){
				switch($destination){
					case 'c1':
						$destination = $col1;
						break;
					case 'c2':
						$destination = $col2;
						break;
					case 'c3':
						$destination = $col3;
						break;
					case 'c4':
						$destination = $col4;
						break;
					case 'c5':
						$destination = $col5;
						break;
					case 'c6':
						$destination = $col6;
						break;
					case 'c7':
						$destination = $col7;
						break;
					case 'f1':
						$destination = $foundation1;
						break;
					case 'f2':
						$destination = $foundation2;
						break;
					case 'f3':
						$destination = $foundation3;
						break;
					case 'f4':
						$destination = $foundation4;
						break;
					default:
						$destination = 'error';
						break;
				}
				if($destination!='error' && $destination!=$origin){
					if($origin==$draw){
						if(count($draw)>0){
							array_push($destination,$draw[0]);
							array_splice($draw,0,1);
							switch($splitter[1]){
								case 'c1':
									$col1 = $destination;
									break;
								case 'c2':
									$col2 = $destination;
									break;
								case 'c3':
									$col3 = $destination;
									break;
								case 'c4':
									$col4 = $destination;
									break;
								case 'c5':
									$col5 = $destination;
									break;
								case 'c6':
									$col6 = $destination;
									break;
								case 'c7':
									$col7 = $destination;
									break;
								case 'f1':
									$foundation1 = $destination;
									break;
								case 'f2':
									$foundation2 = $destination;
									break;
								case 'f3':
									$foundation3 = $destination;
									break;
								case 'f4':
									$foundation4 = $destination;
									break;
							}
						}else{
							echo PHP_EOL . 'No cards in draw pile.' . PHP_EOL . 'Enter to continue' . PHP_EOL;
							stream_get_line(STDIN,1024,PHP_EOL);
						}
						printTable();
					}else{
						if(count($origin)>0){
							$faceUp = 0;
							foreach($origin as $card){
								if($card->pos==1){
									$faceUp++;
								}
							}
							if($faceUp==1){
								$cardCnt = 1;
							}else{
								if($destinationType=='f'){
									$cardCnt = 1;
								}else{
									echo 'How many cards?' . PHP_EOL;
									$cardCnt = stream_get_line(STDIN,1024,PHP_EOL);
								}
							}
							if($cardCnt==trim(strtolower('a')) || $cardCnt==trim(strtolower('all'))){
								$cardCnt = $faceUp;
							}
							for($i = $cardCnt; $i > 0; $i--){
								array_push($destination,$origin[count($origin)-$i]);
							}
							array_splice($origin,count($origin)-$cardCnt);
							if(count($origin)>0){
								$origin[count($origin)-1]->pos = 1;
							}
							switch($splitter[0]){
								case 'c1':
									$col1 = $origin;
									break;
								case 'c2':
									$col2 = $origin;
									break;
								case 'c3':
									$col3 = $origin;
									break;
								case 'c4':
									$col4 = $origin;
									break;
								case 'c5':
									$col5 = $origin;
									break;
								case 'c6':
									$col6 = $origin;
									break;
								case 'c7':
									$col7 = $origin;
									break;
								case 'f1':
									$foundation1 = $origin;
									break;
								case 'f2':
									$foundation2 = $origin;
									break;
								case 'f3':
									$foundation3 = $origin;
									break;
								case 'f4':
									$foundation4 = $origin;
									break;
							}
							switch($splitter[1]){
								case 'c1':
									$col1 = $destination;
									break;
								case 'c2':
									$col2 = $destination;
									break;
								case 'c3':
									$col3 = $destination;
									break;
								case 'c4':
									$col4 = $destination;
									break;
								case 'c5':
									$col5 = $destination;
									break;
								case 'c6':
									$col6 = $destination;
									break;
								case 'c7':
									$col7 = $destination;
									break;
								case 'f1':
									$foundation1 = $destination;
									break;
								case 'f2':
									$foundation2 = $destination;
									break;
								case 'f3':
									$foundation3 = $destination;
									break;
								case 'f4':
									$foundation4 = $destination;
									break;
							}
						}else{
							echo PHP_EOL . 'No  cards in column.' . PHP_EOL . 'Enter to continnue' . PHP_EOL;
							stream_get_line(STDIN,1024,PHP_EOL);
						}
						printTable();
					}
				}else{
					unreadable();
				}
			}else{
				unreadable();
			}
			break;
		default:
			unreadable();
			break;
	}	
  }
?>