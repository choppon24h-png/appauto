<?php
class FPDF{
protected $page=0;protected $n=2;protected $buffer='';protected $pages=array();protected $state=0;
protected $compress=true;protected $k;protected $DefOrientation;protected $CurOrientation;
protected $StdPageSizes;protected $DefPageSize;protected $CurPageSize;protected $CurRotation;
protected $PageInfo;protected $wPt,$hPt;protected $w,$h;protected $lMargin;protected $tMargin;
protected $rMargin;protected $bMargin;protected $cMargin;protected $x,$y;protected $lasth;
protected $LineWidth;protected $fontpath;protected $CoreFonts;protected $fonts;protected $FontFiles;
protected $encodings;protected $cmaps;protected $FontFamily;protected $FontStyle;protected $underline;
protected $CurrentFont;protected $FontSizePt;protected $FontSize;protected $DrawColor;protected $FillColor;
protected $TextColor;protected $ColorFlag;protected $WithAlpha;protected $ws;protected $images;
protected $PageLinks;protected $links;protected $AutoPageBreak;protected $PageBreakTrigger;
protected $InHeader;protected $InFooter;protected $AliasNbPages;protected $ZoomMode;protected $LayoutMode;
protected $metadata;protected $PDFVersion;
function __construct($orientation='P',$unit='mm',$size='A4'){
$this->StdPageSizes=array('a3'=>array(841.89,1190.55),'a4'=>array(595.28,841.89),'a5'=>array(420.94,595.28),'letter'=>array(612,792),'legal'=>array(612,1008));
if($unit=='pt')$this->k=1;elseif($unit=='mm')$this->k=72/25.4;elseif($unit=='cm')$this->k=72/2.54;elseif($unit=='in')$this->k=72;else $this->Error('Incorrect unit: '.$unit);
if(is_string($size)){$size=strtolower($size);if(!isset($this->StdPageSizes[$size]))$this->Error('Unknown page size: '.$size);$a=$this->StdPageSizes[$size];$this->DefPageSize=array($a[0]/$this->k,$a[1]/$this->k);}
else{if($size[0]>$size[1])$a=array($size[1],$size[0]);else $a=$size;$this->DefPageSize=array($a[0]*$this->k,$a[1]*$this->k);}
$this->CurPageSize=$this->DefPageSize;$orientation=strtolower($orientation);
if($orientation=='p'||$orientation=='portrait'){$this->DefOrientation='P';$this->w=$this->DefPageSize[0];$this->h=$this->DefPageSize[1];}
elseif($orientation=='l'||$orientation=='landscape'){$this->DefOrientation='L';$this->w=$this->DefPageSize[1];$this->h=$this->DefPageSize[0];}
else $this->Error('Incorrect orientation: '.$orientation);
$this->CurOrientation=$this->DefOrientation;$this->wPt=$this->w*$this->k;$this->hPt=$this->h*$this->k;
$margin=28.35/$this->k;$this->SetMargins($margin,$margin);$this->cMargin=$margin/10;
$this->LineWidth=.567/$this->k;$this->SetAutoPageBreak(true,2*$margin);
$this->SetDisplayMode('default');$this->SetCompression(true);$this->PDFVersion='1.3';}
function SetMargins($left,$top,$right=null){$this->lMargin=$left;$this->tMargin=$top;if($right===null)$right=$left;$this->rMargin=$right;}
function SetLeftMargin($margin){$this->lMargin=$margin;if($this->page>0&&$this->x<$margin)$this->x=$margin;}
function SetTopMargin($margin){$this->tMargin=$margin;}
function SetRightMargin($margin){$this->rMargin=$margin;}
function SetAutoPageBreak($auto,$margin=0){$this->AutoPageBreak=$auto;$this->bMargin=$margin;$this->PageBreakTrigger=$this->h-$margin;}
function SetDisplayMode($zoom,$layout='default'){if($zoom=='fullpage'||$zoom=='fullwidth'||$zoom=='real'||$zoom=='default'||!is_string($zoom))$this->ZoomMode=$zoom;else $this->Error('Incorrect zoom display mode: '.$zoom);
if($layout=='single'||$layout=='continuous'||$layout=='two'||$layout=='default')$this->LayoutMode=$layout;else $this->Error('Incorrect layout display mode: '.$layout);}
function SetCompression($compress){$this->compress=$compress;}
function SetTitle($title,$isUTF8=false){$this->metadata['Title']=$isUTF8?$title:utf8_encode($title);}
function SetAuthor($author,$isUTF8=false){$this->metadata['Author']=$isUTF8?$author:utf8_encode($author);}
function SetSubject($subject,$isUTF8=false){$this->metadata['Subject']=$isUTF8?$subject:utf8_encode($subject);}
function SetKeywords($keywords,$isUTF8=false){$this->metadata['Keywords']=$isUTF8?$keywords:utf8_encode($keywords);}
function SetCreator($creator,$isUTF8=false){$this->metadata['Creator']=$isUTF8?$creator:utf8_encode($creator);}
function AddPage($orientation='',$size='',$rotation=0){
if($this->state==3)$this->Error('The document is closed');
$family=$this->FontFamily;$style=$this->FontStyle.($this->underline?'U':'');$fontsize=$this->FontSizePt;
$lw=$this->LineWidth;$dc=$this->DrawColor;$fc=$this->FillColor;$tc=$this->TextColor;$cf=$this->ColorFlag;
if($this->page>0){$this->_endpage();$this->_beginpage($orientation,$size,$rotation);
$this->_out('2 J');$this->LineWidth=$lw;$this->_out(sprintf('%.2F w',$lw*$this->k));
if($family)$this->SetFont($family,$style,$fontsize);$this->DrawColor=$dc;if($dc!='0 G')$this->_out($dc);
$this->FillColor=$fc;if($fc!='0 g')$this->_out($fc);$this->TextColor=$tc;$this->ColorFlag=$cf;}
else{$this->_beginpage($orientation,$size,$rotation);$this->_out('2 J');
$this->LineWidth=$lw;$this->_out(sprintf('%.2F w',$lw*$this->k));}
}
function SetFont($family,$style='',$size=0){
if($family=='')$family=$this->FontFamily;else $family=strtolower($family);
$style=strtoupper($style);if(strpos($style,'U')!==false){$this->underline=true;$style=str_replace('U','',$style);}else $this->underline=false;
if($style=='IB')$style='BI';if($size==0)$size=$this->FontSizePt;
$this->FontFamily=$family;$this->FontStyle=$style;$this->FontSizePt=$size;$this->FontSize=$size/$this->k;
$this->CurrentFont=&$this->fonts[$family.$style];if($this->page>0)$this->_out(sprintf('BT /F%d %.2F Tf ET',$this->CurrentFont['i'],$this->FontSizePt));}
function SetFontSize($size){if($this->FontSizePt==$size)return;$this->FontSizePt=$size;$this->FontSize=$size/$this->k;if($this->page>0)$this->_out(sprintf('BT /F%d %.2F Tf ET',$this->CurrentFont['i'],$this->FontSizePt));}
function SetTextColor($r,$g=null,$b=null){if((($r==0&&$g==0&&$b==0)||$g===null))$this->TextColor=sprintf('%.3F g',$r/255);else $this->TextColor=sprintf('%.3F %.3F %.3F rg',$r/255,$g/255,$b/255);$this->ColorFlag=($this->FillColor!=$this->TextColor);}
function Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=false,$link=''){
$k=$this->k;if($this->y+$h>$this->PageBreakTrigger&&!$this->InHeader&&!$this->InFooter&&$this->AcceptPageBreak()){
$x=$this->x;$ws=$this->ws;if($ws>0){$this->ws=0;$this->_out('0 Tw');}$this->AddPage($this->CurOrientation,$this->CurPageSize,$this->CurRotation);
$this->x=$x;if($ws>0){$this->ws=$ws;$this->_out(sprintf('%.3F Tw',$ws*$k));}}
if($w==0)$w=$this->w-$this->rMargin-$this->x;$s='';
if($fill||$border==1){if($fill)$op=($border==1)?'B':'f';else $op='S';
$s=sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);}
if(is_string($border)){$x=$this->x;$y=$this->y;if(strpos($border,'L')!==false)$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
if(strpos($border,'T')!==false)$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
if(strpos($border,'R')!==false)$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
if(strpos($border,'B')!==false)$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);}
if($txt!==''){if(!isset($this->CurrentFont))$this->Error('No font has been set');
if($align=='R')$dx=$w-$this->cMargin-$this->GetStringWidth($txt);elseif($align=='C')$dx=($w-$this->GetStringWidth($txt))/2;else $dx=$this->cMargin;
if($this->ColorFlag)$s.='q '.$this->TextColor.' ';
$s.=sprintf('BT %.2F %.2F Td (%s) Tj ET',($this->x+$dx)*$k,($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$this->_escape($txt));
if($this->underline)$s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
if($this->ColorFlag)$s.=' Q';if($link)$this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$this->GetStringWidth($txt),$this->FontSize,$link);}
if($s)$this->_out($s);$this->lasth=$h;if($ln>0){$this->y+=$h;if($ln==1)$this->x=$this->lMargin;}else $this->x+=$w;}
function Ln($h=null){$this->x=$this->lMargin;if($h===null)$this->y+=$this->lasth;else $this->y+=$h;}
function GetStringWidth($s){if(!isset($this->CurrentFont))$this->Error('No font has been set');$w=0;$l=strlen($s);for($i=0;$i<$l;$i++)$w+=550;return $w*$this->FontSize/1000;}
function Output($dest='',$name='',$isUTF8=false){
if($this->state<3)$this->Close();
if($dest==''){$dest='I';$name='doc.pdf';}
switch($dest){
case 'I':header('Content-Type: application/pdf');header('Content-Disposition: inline; filename="'.$name.'"');header('Cache-Control: private, max-age=0, must-revalidate');header('Pragma: public');echo $this->buffer;break;
case 'D':header('Content-Type: application/pdf');header('Content-Disposition: attachment; filename="'.$name.'"');header('Cache-Control: private, max-age=0, must-revalidate');header('Pragma: public');echo $this->buffer;break;
case 'F':if(!$f=@fopen($name,'wb'))$this->Error('Unable to create output file: '.$name);fwrite($f,$this->buffer);fclose($f);break;
case 'S':return $this->buffer;default:$this->Error('Incorrect output destination: '.$dest);}}
function Close(){if($this->state==3)return;if($this->page==0)$this->AddPage();$this->_endpage();$this->_enddoc();}
protected function _beginpage($orientation,$size,$rotation){$this->page++;$this->pages[$this->page]='';$this->state=2;$this->x=$this->lMargin;$this->y=$this->tMargin;$this->FontFamily='';
if(!$orientation)$orientation=$this->DefOrientation;else{$orientation=strtoupper($orientation);if($orientation!=$this->DefOrientation)$this->OrientationChanges[$this->page]=true;}
if(!$size)$size=$this->DefPageSize;else{if($size[0]>$size[1])$a=array($size[1],$size[0]);else $a=$size;if($a[0]!=$this->DefPageSize[0]||$a[1]!=$this->DefPageSize[1])$this->PageSizes[$this->page]=array($a[0]*$this->k,$a[1]*$this->k);}
if($orientation!=$this->CurOrientation){if($orientation=='P'){$this->w=$this->DefPageSize[0];$this->h=$this->DefPageSize[1];}else{$this->w=$this->DefPageSize[1];$this->h=$this->DefPageSize[0];}
$this->wPt=$this->w*$this->k;$this->hPt=$this->h*$this->k;$this->PageBreakTrigger=$this->h-$this->bMargin;$this->CurOrientation=$orientation;}
if($orientation!=$this->DefOrientation||$size[0]!=$this->DefPageSize[0]||$size[1]!=$this->DefPageSize[1])$this->CurPageSize=array($this->wPt,$this->hPt);
if($rotation!=0){if($rotation%90!=0)$this->Error('Incorrect rotation value: '.$rotation);$this->CurRotation=$rotation;if($orientation!=$this->DefOrientation)$this->PageSizes[$this->page]=array($this->hPt,$this->wPt);}}
protected function _endpage(){$this->state=1;}
protected function _enddoc(){$this->buffer='%PDF-1.3'."\n";$this->_putpages();$this->_putresources();
$this->buffer.='1 0 obj'."\n".'<<'."\n".'/Type /Catalog'."\n".'/Pages 2 0 R'."\n".'>>'."\n".'endobj'."\n";
$this->buffer.='xref'."\n".'0 '.($this->n+1)."\n".'0000000000 65535 f '."\n";
$this->buffer.='trailer'."\n".'<<'."\n".'/Size '.($this->n+1)."\n".'/Root 1 0 R'."\n".'>>'."\n".'startxref'."\n".'0'."\n".'%%EOF'."\n";$this->state=3;}
protected function _putpages(){$nb=$this->page;for($n=1;$n<=$nb;$n++){$this->_putpage($n);}}
protected function _putpage($n){$this->buffer.='3 0 obj'."\n".'<<'."\n".'/Type /Page'."\n".'/Parent 2 0 R'."\n".'/Contents 4 0 R'."\n".'>>'."\n".'endobj'."\n";
$this->buffer.='4 0 obj'."\n".'<<'."\n".'/Length '.strlen($this->pages[$n])."\n".'>>'."\n".'stream'."\n".$this->pages[$n]."\n".'endstream'."\n".'endobj'."\n";}
protected function _putresources(){$this->buffer.='2 0 obj'."\n".'<<'."\n".'/Type /Pages'."\n".'/Kids [3 0 R]'."\n".'/Count 1'."\n".'>>'."\n".'endobj'."\n";}
protected function _out($s){if($this->state==2)$this->pages[$this->page].=$s."\n";else $this->buffer.=$s."\n";}
protected function _escape($s){return str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$s)));}
protected function _dounderline($x,$y,$txt){$up=-100;$ut=50;$w=$this->GetStringWidth($txt)+$this->ws*substr_count($txt,' ');return sprintf('%.2F %.2F %.2F %.2F re f',$x*$this->k,($this->h-($y-$up/1000*$this->FontSize))*$this->k,$w*$this->k,-$ut/1000*$this->FontSizePt);}
function Error($msg){throw new Exception('FPDF error: '.$msg);}
function AcceptPageBreak(){return $this->AutoPageBreak;}}
