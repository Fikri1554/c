<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExtendCrewEvaluation extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url')); 
	}
	
    function getDataPage($idPerson, $personName, $rank, $vessel, $masterName, $chiefName, $chiefRank)
	{
		$dataOut = array();

		$dataOut['idperson']     = base64_decode($idPerson);
		$dataOut['idpersonEncrypted'] = base64_encode(base64_encode(base64_encode($dataOut['idperson'])));
		$dataOut['personName']   = base64_decode($personName);
		$dataOut['rank']         = base64_decode($rank);
		$dataOut['vessel']       = base64_decode($vessel);
		$dataOut['masterName']   = base64_decode($masterName);
		$dataOut['chiefName']    = base64_decode($chiefName);
		$dataOut['chiefRank']    = base64_decode($chiefRank);

		$this->load->view('frontend/extendCrewEvaluation', $dataOut);
	}
	
    function saveDataCrewEvaluation() {
		$data = $_POST;
		$dataIns = array();
		$criteriaData = array();
		$stData = "";
		$idPerson = base64_decode(base64_decode(base64_decode($data['txtIdPerson'])));
		$userDateTimeNow = $this->session->userdata('userCrewSystem') . "/" . date('Ymd') . "/" . date('H:i:s');
 		
		try {
			$dataIns['vessel'] = $data['vessel'];
			$dataIns['seafarer_name'] = $data['personName'];
			$dataIns['rank'] = $data['rank'];
			$dataIns['date_of_report'] = $data['txtDateOfReport'];
			$dataIns['reporting_period_from'] = $data['txtDateOfReportingPeriodFrom'];
			$dataIns['reporting_period_to'] = $data['txtDateOfReportingPeriodTo'];
			$dataIns['idperson'] = $idPerson;
			$dataIns['reason_midway_contract'] = $data['reasonMidway'];
			$dataIns['reason_signing_off'] = $data['reasonSigningOff'];
			$dataIns['reason_leaving_vessel'] = $data['reasonLeaving'];
			$dataIns['reason_special_request'] = $data['reasonSpecialRequest'];
			$dataIns['master_comments'] = $data['txtMasterComments'];
			$dataIns['reporting_officer_comments'] = $data['txtOfficerComments'];
			$dataIns['promote'] = $data['txtPromoted'];
			$dataIns['re_employ'] = $data['txtReemploy'];
			$dataIns['reporting_officer_name'] = $data['chiefName'];
			$dataIns['reporting_officer_rank'] = $data['chiefRank'];
			$dataIns['mastercoofullname'] = $data['masterName'];
			$dataIns['received_by_cm'] = $data['txtreceived'];
			$dataIns['date_of_receipt'] = $data['txtDateReceipt'];
			
			$txtIdEditCrew  = $this->MCrewscv->insData('crew_evaluation_report',$dataIns,'aaaaa');
			$this->MCrewscv->updateData(array('id' => $txtIdEditCrew), array('st_submit_chief' => 'Y'),'crew_evaluation_report');
			$this->addDataMyAppLetter($txtIdEditCrew);
				
			$criteriaList = array(
				"Ability/Knowledge of Job" => "ability",
				"Safety Consciousness" => "safety",
				"Dependability & Integrity" => "integrity",
				"Initiative" => "initiative",
				"Conduct" => "conduct",
				"Ability to get on with others" => "abilityGetOn",
				"Appearance (+ uniforms)" => "appearance",
				"Sobriety" => "sobriety",
				"English Language" => "english",
				"Leadership (Officers)" => "leadership"
			);

			foreach ($criteriaList as $criteriaName => $criteriaId) {
				$value = isset($data[$criteriaId]) ? $data[$criteriaId] : '';
				$criteriaData = array(
					'idperson' => $idPerson,
					'id_report' => $txtIdEditCrew,
					'criteria_name' => $criteriaName,
					'excellent' => ($value == '4') ? 'Y' : '',
					'good' => ($value == '3') ? 'Y' : '',
					'fair' => ($value == '2') ? 'Y' : '',
					'poor' => ($value == '1') ? 'Y' : '',
					'identify' => isset($data["txtIdentify" . ucfirst($criteriaId)]) ? $data["txtIdentify" . ucfirst($criteriaId)] : '',
					'addUsrDate' => $userDateTimeNow
				);
				$this->MCrewscv->insData("crew_evaluation_criteria", $criteriaData);
			}
			
			$stData = array(
				"status" => "success",
				"message" => "Save Success..!!",
				"id" => $txtIdEditCrew
			);

		} catch (\Throwable $th) {
			$stData = array(
				"status" => "error",
				"message" => "Error: " . $th->getMessage()
			);
		}

		echo json_encode($stData);
	}

	function generateLink()
	{
		$data = $_POST;
		$idPerson = $data['txtModalGenLinkIdPerson'];
		$department = $data['department'];

		$sql = "SELECT 
			A.idperson,
			TRIM(CONCAT(D.fname, ' ', COALESCE(D.mname, ''), ' ', D.lname)) AS fullName,
			B.nmrank,
			C.nmvsl,
			
			(SELECT TRIM(CONCAT(MP.fname, ' ', COALESCE(MP.mname, ''), ' ', MP.lname))
			FROM tblcontract TC
			JOIN mstpersonal MP ON MP.idperson = TC.idperson
			WHERE TC.signonvsl = A.signonvsl 
			AND TC.signonrank = '044' 
			AND TC.signoffdt = '0000-00-00'
			LIMIT 1) AS co_name,

			(SELECT TRIM(CONCAT(MP.fname, ' ', COALESCE(MP.mname, ''), ' ', MP.lname))
			FROM tblcontract TC
			JOIN mstpersonal MP ON MP.idperson = TC.idperson
			WHERE TC.signonvsl = A.signonvsl 
			AND TC.signonrank = '041' 
			AND TC.signoffdt = '0000-00-00'
			LIMIT 1) AS ce_name,

			(SELECT TRIM(CONCAT(MP.fname, ' ', COALESCE(MP.mname, ''), ' ', MP.lname))
			FROM tblcontract TC
			JOIN mstpersonal MP ON MP.idperson = TC.idperson
			WHERE TC.signonvsl = A.signonvsl 
			AND TC.signonrank = '037' 
			AND TC.signoffdt = '0000-00-00'
			LIMIT 1) AS mastername

		FROM tblcontract A
		LEFT JOIN mstrank B ON B.kdrank = A.signonrank
		LEFT JOIN mstvessel C ON C.kdvsl = A.signonvsl
		LEFT JOIN mstpersonal D ON D.idperson = A.idperson
		WHERE A.signoffdt = '0000-00-00' 
		AND A.idperson = '".$idPerson."'";

		$crewData = $this->MCrewscv->getDataQuery($sql);

		$personName = $crewData[0]->fullName;
		$rank = $crewData[0]->nmrank;
		$vessel = $crewData[0]->nmvsl;
		$masterName = $crewData[0]->mastername;
		$chiefName = "";
		$chiefRank = "";

		if($department == "ENGINE") {
			$chiefName = $crewData[0]->ce_name;
			$chiefRank = "C/E";
		} 
		else if($department == "DECK") {
			$chiefName = $crewData[0]->co_name;
			$chiefRank = "C/O";
		}
		
		$encoded = array(
			'idperson'    => base64_encode($idPerson),
			'personName'  => base64_encode($personName),
			'rank'        => base64_encode($rank),
			'vessel'      => base64_encode($vessel),
			'masterName'  => base64_encode($masterName),
			'chiefName'      => base64_encode($chiefName),
			'chiefRank'      => base64_encode($chiefRank),
		);

		echo json_encode(array(
			'url' => base_url("extendCrewEvaluation/getDataPage/" .
				$encoded['idperson'] . '/' .
				$encoded['personName'] . '/' .
				$encoded['rank'] . '/' .
				$encoded['vessel'] . '/' .
				$encoded['masterName'] . '/' .
				$encoded['chiefName'] . '/' .	
				$encoded['chiefRank']
			),
		));
	}

	function exportPDFCrewEvaluation($id_report) {
		$dataOut = array();
		$label_reject = "";
		 
		function getChecked($value) {
			return ($value === 'Y') ? '&#10004;' : '';
		}
		
		$sqlReport = "SELECT * FROM crew_evaluation_report WHERE id = '".$id_report."' AND deletests = 0";		
		$reportData = $this->MCrewscv->getDataQuery($sqlReport);

		$row = $reportData[0];

		$vessel = $row->vessel;
		$seafarerName = $row->seafarer_name;
		$rank = $row->rank;
		$dateOfReport = $row->date_of_report;
		$reportPeriodFrom = $row->reporting_period_from;
		$reportPeriodTo = $row->reporting_period_to;
		$masterComments = $row->master_comments;
		$reportingOfficerComments = $row->reporting_officer_comments;
		$promote = $row->promote;
		$reportingOfficerName = $row->reporting_officer_name;
		$reportingOfficerRank = $row->reporting_officer_rank;
		$mastercoofullname = $row->mastercoofullname;
		$receivedByCM = $row->received_by_cm;
		$dateOfReceipt = $row->date_of_receipt;
		$reEmploy = $row->re_employ;
		$remark_reject = $row->remark_reject;
		
		if ($row->st_reject == 'Y') {
			$label_reject = '<span class="badge badge-danger" style="font-size:18px;padding:10px;background-color:red;margin-left:20px;">REJECTED</span>';
		}

		$reasonMidway = getChecked($row->reason_midway_contract);
		$reasonLeaving = getChecked($row->reason_leaving_vessel);
		$reasonSigningOff = getChecked($row->reason_signing_off);
		$reasonSpecial = getChecked($row->reason_special_request);

		$qrCodePathChief = !empty($row->qrcode_reporting_chief) ? base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_chief) : '';
		$qrCodePathMaster = !empty($row->qrcode_reporting_master) ? base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_master) : '';
		$qrCodePathSeafarer = !empty($row->qrcode_seafarer) ? base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_seafarer) : '';
		$qrCodePathCM = !empty($row->qrcode_reporting_cm) ? base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_cm) : '';

		$sqlCriteria = "SELECT * FROM crew_evaluation_criteria 
						WHERE deletests = '0' AND id_report = '".intval($id_report)."' ORDER BY id ASC";
		$criteriaData = $this->MCrewscv->getDataQuery($sqlCriteria);

		$criteriaTable = '';
		if (count($criteriaData) > 0) {
			foreach ($criteriaData as $criteriaRow) {
				$criteriaTable .= '<tr>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: left;">'.$criteriaRow->criteria_name.'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->excellent).'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->good).'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->fair).'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->poor).'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.$criteriaRow->identify.'</td>';
				$criteriaTable .= '</tr>';
			}
		}
		
		$dataOut = array(
			'id_report' => $id_report,
			'vessel' => $vessel,
			'seafarerName' => $seafarerName,
			'rank' => $rank,
			'dateOfReport' => $dateOfReport,
			'reportPeriodFrom' => $reportPeriodFrom,
			'reportPeriodTo' => $reportPeriodTo,
			'reasonMidway' => $reasonMidway,
			'reasonLeaving' => $reasonLeaving,
			'reasonSigningOff' => $reasonSigningOff,
			'reasonSpecial' => $reasonSpecial,
			'criteriaTable' => $criteriaTable,
			'masterComments' => $masterComments,
			'reportingOfficerComments' => $reportingOfficerComments,
			'promote' => $promote,
			'reportingOfficerName' => $reportingOfficerName,
			'reportingOfficerRank' => $reportingOfficerRank,
			'mastercoofullname' => $mastercoofullname,
			'receivedByCM' => $receivedByCM,
			'dateOfReceipt' => $dateOfReceipt,
			'reEmploy' => $reEmploy,
			'qrCodeImg' => $qrCodePathChief,
			'qrCodePathMaster' => $qrCodePathMaster,
			'qrCodePathSeafarer' => $qrCodePathSeafarer,
			'qrCodePathCM' => $qrCodePathCM,
			'remark_reject' => $remark_reject,
			'label_reject' => $label_reject,
		);

		require("application/views/frontend/pdf/mpdf60/mpdf.php");
		$mpdf = new mPDF('utf-8', 'A4');
		ob_start();
		$this->load->view('frontend/exportPDFCrewEvaluation', $dataOut);
		$html = ob_get_contents();
		ob_end_clean();
		$mpdf->WriteHTML(utf8_encode($html)); v 
		$mpdf->Output("Crew_Evaluation_Report_".$seafarerName.".pdf", 'I');
		exit;
	}
	
	function printCrewEvaluation($idPerson = "") {
		$dataOut = array();
		$decryptedId = base64_decode(base64_decode(base64_decode($idPerson)));

		$vessel = '';
		$reEmploy = '';
		$seafarerName = '';
		$rank = '';
		$dateOfReport = '';
		$reportPeriodFrom = '';
		$reportPeriodTo = '';
		$masterComments = '';
		$reportingOfficerComments = '';
		$promote = '';
		$reportingOfficerName = '';
		$reportingOfficerRank = '';
		$receivedByCM = '';
		$dateOfReceipt = '';
		$mastercoofullname = '';
		$reasonMidway = '';
		$reasonLeaving = '';
		$reasonSigningOff = '';
		$reasonSpecial = '';
		$criteriaTable = '';
		$qrCodePathChief = '';
		$qrCodePathMaster = '';
		$qrCodePathSeafarer = '';
		$qrCodePathCM = '';
		$Btnact = "";
		$Btnreject = "";
		$remark_reject = "";
		$label_reject = "";

		function getChecked($value) {
			return ($value === 'Y') ? '&#10004;' : '';
		}
		
		$sqlReport = "SELECT * FROM crew_evaluation_report WHERE idperson = '".$decryptedId."' ORDER BY id DESC LIMIT 0,1";
		$reportData = $this->MCrewscv->getDataQuery($sqlReport);  

		$sqlCriteria = "SELECT * FROM crew_evaluation_criteria 
					WHERE deletests = '0' AND id_report = '".$reportData[0]->id."' ORDER BY id ASC";
		$criteriaData = $this->MCrewscv->getDataQuery($sqlCriteria);
	
		if (count($reportData) > 0) {
			$row = $reportData[0];

			$vessel = $row->vessel;
			$seafarerName = $row->seafarer_name;
			$rank = $row->rank;
			$dateOfReport = $row->date_of_report;
			$reportPeriodFrom = $row->reporting_period_from;
			$reportPeriodTo = $row->reporting_period_to;
			$masterComments = $row->master_comments;
			$reportingOfficerComments = $row->reporting_officer_comments;
			$promote = $row->promote;
			$reportingOfficerName = $row->reporting_officer_name;
			$reportingOfficerRank = $row->reporting_officer_rank;
			$mastercoofullname = $row->mastercoofullname;
			$receivedByCM = $row->received_by_cm;
			$dateOfReceipt = $row->date_of_receipt;
			$reEmploy = $row->re_employ;
			$remark_reject = $row->remark_reject;

			$reasonMidway = getChecked($row->reason_midway_contract);
			$reasonLeaving = getChecked($row->reason_leaving_vessel);
			$reasonSigningOff = getChecked($row->reason_signing_off);
			$reasonSpecial = getChecked($row->reason_special_request);
			
			if (!empty($row->qrcode_reporting_chief)) {
				$qrCodePathChief = base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_chief);
			}
			if (!empty($row->qrcode_reporting_master)) {
				$qrCodePathMaster = base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_master);
			}
			if (!empty($row->qrcode_seafarer)) {
				$qrCodePathSeafarer = base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_seafarer);
			}
			if (!empty($row->qrcode_reporting_cm)) {
				$qrCodePathCM = base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_cm);
			}

			if (count($criteriaData) > 0) {
				foreach ($criteriaData as $criteriaRow) {
					$criteriaTable .= '<tr>';
						$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: left;">'.$criteriaRow->criteria_name.'</td>';
						$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->excellent).'</td>';
						$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->good).'</td>';
						$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->fair).'</td>';
						$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->poor).'</td>';
						$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.$criteriaRow->identify.'</td>';
					$criteriaTable .= '</tr>';
				}
			}
		}
		
		if ($reportData[0]->st_submit_chief == 'Y' && $reportData[0]->st_submit_master == 'N') {
			$Btnact = '
				<div class="col-md-6 d-flex align-items-end">
					<button type="button" class="btn btn-primary btn-block" id="btnApproveMaster" onclick="approveMaster();"><i class="fa fa-thumbs-up" style="font-size:15px"> Approve Master</i></button>
				</div>
			';
			$masterComments = '
				<div class="col-md-6">
					<label class="form-label">Master Comments</label>
					<textarea class="form-control" name="comments_master" rows="6" placeholder="Master\'s comments"
						id="txtMasterComments"></textarea>
				</div>
			';
		} else if ($reportData[0]->st_submit_master == 'Y' && $reportData[0]->st_submit_seafarer == 'N') {
			$Btnact = '<button type="button" class="btn btn-primary btn-block" id="btnApproveSeafarer" onclick="approveSeafarer();"><i class="fa fa-thumbs-up" style="font-size:15px"> Approve Seafarer</i></button>';
		} else if ($reportData[0]->st_submit_seafarer == 'Y' && $reportData[0]->st_submit_cm == 'N') {
			if ($row->st_reject !== 'Y') {
				$Btnact = '<button type="button" class="btn btn-primary btn-block" id="btnApproveCM" onclick="approveCM();"><i class="fa fa-thumbs-up" style="font-size:15px"> Approve CM</i></button>';
				$dateOfReceipt = '
					<div class="col-md-6">
						<label class="form-label">Date of Receipt</label>
						<input type="date" class="form-control" id="txtDateReceipt">
					</div>';
				$Btnreject = '<button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#evaluasiModal"><i class="fa fa-ban" style="font-size:15px"> Reject</i></button>';
			} else if ($reportData[0]->st_submit_cm == 'Y' || $row->st_reject == 'Y') {
				$Btnact = '<button type="button" class="btn btn-primary btn-block" id="btnPrintCrewEvaluation" onclick="exportPDF();">Print Crew Evaluation</button>';
				
				if ($row->st_reject == 'Y') {
					$label_reject = '<span class="badge badge-danger" style="font-size:20px;padding:10px;background-color:red;">REJECTED</span>';
				}
			}
		} else if ($reportData[0]->st_submit_cm == 'Y') {
			$Btnact = '<button type="button" class="btn btn-primary btn-block" id="btnPrintCrewEvaluation" onclick="exportPDF();">Print Crew Evaluation</button>';
		}

		$dataOut = array(
			'id_report' => $reportData[0]->id,
			'vessel' => $vessel,
			'seafarerName' => $seafarerName,
			'rank' => $rank,
			'dateOfReport' => $dateOfReport,
			'reportPeriodFrom' => $reportPeriodFrom,
			'reportPeriodTo' => $reportPeriodTo,
			'reasonMidway' => $reasonMidway,
			'reasonLeaving' => $reasonLeaving,
			'reasonSigningOff' => $reasonSigningOff,
			'reasonSpecial' => $reasonSpecial,
			'criteriaTable' => $criteriaTable,
			'masterComments' => $masterComments,
			'reportingOfficerComments' => $reportingOfficerComments,
			'promote' => $promote,
			'reportingOfficerName' => $reportingOfficerName,
			'reportingOfficerRank' => $reportingOfficerRank,
			'mastercoofullname' => $mastercoofullname,
			'receivedByCM' => $receivedByCM,
			'dateOfReceipt' => $dateOfReceipt,
			'reEmploy' => $reEmploy,
			'qrCodeImg' => $qrCodePathChief,
			'qrCodePathMaster' => $qrCodePathMaster,
			'qrCodePathSeafarer' => $qrCodePathSeafarer,
			'qrCodePathCM' => $qrCodePathCM,
			'btnAct' => $Btnact,
			'btnReject' => $Btnreject,
			'remark_reject' => $remark_reject,
			'label_reject' => $label_reject,
			'masterComments' => $masterComments
		);
		$this->load->view('frontend/reportCrewEvaluation', $dataOut);
	}

	function getBatchNo()
	{
		$batchNo = "1";
		$sql = " SELECT (batchno + 1) AS batchNo FROM tblempnosurat ORDER BY batchno DESC LIMIT 0,1 ";
		$data = $this->MCrewscv->getDataQueryDB6($sql);

		if(count($data) > 0)
		{
			$batchNo = $data[0]->batchNo;
		}

		return $batchNo;
	}

	function createQRCode($id = "", $type = '')
	{
		$config = array();
		$this->load->library('ciqrcode');

		$config['cacheable']	= true;
		$config['cachedir']		= './assets/imgQRCodeCrewCV/';
		$config['errorlog']		= './assets/imgQRCodeCrewCV/';
		$config['imagedir']		= './assets/imgQRCodeCrewCV/';
		$config['quality']		= true;
		$config['size']			= '1024';
		$config['black']		= array(224,255,255);
		$config['white']		= array(0,0,128);
		$this->ciqrcode->initialize($config);
			
		$imgName = base64_encode($id).'.jpg';
		
		if($type == 'approveMaster')
		{
			$imgName = 'approveMaster_'.base64_encode($id).'.jpg';
		}
		if($type == 'approveChief')
		{
			$imgName = 'approveChief_'.base64_encode($id).'.jpg';
		}
		if($type == 'approveCM')
		{
			$imgName = 'approveCM_'.base64_encode($id).'.jpg';
		}
		if($type == 'approveSeafarer')
		{
			$imgName = 'approveSeafarer_'.base64_encode($id).'.jpg';
		}
		
		$params['data'] = "http://apps.andhika.com/observasi/myLetter/viewLetter/".base64_encode($id); 
		$params['level'] = 'H'; 
		$params['size'] = 5;
		$params['savename'] = FCPATH.$config['imagedir'].$imgName; 
		$params['logo'] = "./assets/img/andhika.png";

		$this->ciqrcode->generate($params); 

    	return $imgName;
	}

	function createNo($noNya = "")
	{
		$dt = strlen($noNya);
		$outNo = "";
		if($dt == 1)
		{
			$outNo = "000".$noNya;
		}
		else if($dt == 2)
		{
			$outNo = "00".$noNya;
		}
		else if($dt == 3)
		{
			$outNo = "0".$noNya;
		}
		else{
			$outNo = $noNya;
		}
		
		return $outNo;
	}

	function addDataMyAppLetter($txtIdEditCrew = "") 
	{
		$dateNow = date("Y-m-d");
		$yearNow = date("Y");
		$monthNow = date("m");
		$noSurat = "1";
		$initDivisi = "DKP";
		$initCmp = "AES";
		$insSql = array();
		$imgName = "";
 
		try {
			$sql = "SELECT * FROM crew_evaluation_report WHERE id = '".$txtIdEditCrew."' AND deletests = '0'";
			$rsl = $this->MCrewscv->getDataQuery($sql);
			
			if ($initCmp !== "") {
				$sqlSrv = "SELECT nosurat FROM tblEmpNoSurat
						WHERE cmpcode = '".$initCmp."' AND YEAR(tglsurat) = '".$yearNow."'
						ORDER BY nosurat DESC LIMIT 0,1";
				$rslSrv = $this->MCrewscv->getDataQueryDB6($sqlSrv);

				if (count($rslSrv) > 0) {
					$ns = explode("/", $rslSrv[0]->nosurat);
					$noSurat = $ns[0] + 1;
				}

				$batchno = $this->getBatchNo();
				$formatNoSrt = $this->createNo($noSurat) . "/" . $initCmp . "/" . $initDivisi . "/" . $monthNow . substr($yearNow, 2, 2);

				$insSql["batchno"] = $batchno;
				$insSql["cmpcode"] = $initCmp;
				$insSql["nosurat"] = $formatNoSrt;
				$insSql["issueddiv"] = $initDivisi;
				$insSql["signedby"] = $initDivisi;
				$insSql["address"] = "Crewing";
				$insSql["tglsurat"] = $dateNow;
				$insSql["ket"] = "Crew Evaluation Report / Crewing ";
				$insSql["copydoc"] = "0";
				$insSql["canceldoc"] = "0";

				$this->MCrewscv->insDataDb6($insSql,"tblEmpNoSurat");
				$insSql = array();	
				$imgName = $this->createQRCode($batchno, 'approveChief');
				
				$insSql["batchno"] = $batchno;
				$insSql["qrcode_reporting_chief"] = $imgName;

				$whereNya = "id = '".$txtIdEditCrew."'";
				$this->MCrewscv->updateData($whereNya, $insSql, "crew_evaluation_report");
				
			}
		} catch (Exception $e) {
			$imgName = "Failed => " . $e->getMessage();
		}
		return $imgName;
	}

	function approveMaster() {
		$data = $_POST;
		$txtIdReport = $data['txtIdReport'];
		$comments = $data['txtMasterComments'];
		try {
			$qrCodeFileName = $this->createQRCode($txtIdReport, 'approveMaster');
			$updateData = array(
				'master_comments' => $comments,
				'qrcode_reporting_master' => $qrCodeFileName,
				'st_submit_master' => 'Y'
			);
			$this->MCrewscv->updateData(
				array('id' => $txtIdReport),
				$updateData,
				'crew_evaluation_report'
			);
			$response = array(
				'status' => 'success',
				'message' => 'Approved Master successfully!'
			);
		} catch (Exception $e) {
			$response = array(
				'status' => 'error',
				'message' => 'Error: ' . $e->getMessage()
			);
		}
		echo json_encode($response);
	}

	function approveSeafarer() {
		$data = $_POST;
		$txtIdReport = $data['txtIdReport'];

		try {
			$qrCodeFileName = $this->createQRCode($txtIdReport, 'approveSeafarer');
			$updateData = array(
				'qrcode_seafarer' => $qrCodeFileName,
				'st_submit_seafarer' => 'Y'
			);

			$this->MCrewscv->updateData(
				array('id' => $txtIdReport),
				$updateData,
				'crew_evaluation_report'
			);

			$sql = "SELECT * FROM crew_evaluation_report WHERE id = '".$txtIdReport."' AND deletests = 0";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if (count($rsl) > 0) {
				$idPerson = $rsl[0]->idperson;
				$idPersonEncoded = base64_encode(base64_encode(base64_encode($idPerson)));

				$this->sendApprovalNotification($idPersonEncoded, 'muhamad.fikri@andhika.com');
			}

			$response = array(
				'status' => 'success',
				'message' => 'Approved Seafarer successfully!'
			);
		} catch (Exception $e) {
			$response = array(
				'status' => 'error',
				'message' => 'Error: ' . $e->getMessage()
			);
		}
		echo json_encode($response);
	}
 
	function sendApprovalNotification($idPersonEncoded, $recipientEmail) {
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.phpmailer.php';
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.smtp.php';

		$link = base_url("extendCrewEvaluation/printCrewEvaluation/$idPersonEncoded");

		$mail = new PHPMailer();

		try {
			$mail->isSMTP();
			$mail->Host       = 'smtp.zoho.com';
			$mail->SMTPAuth   = true;
			$mail->Username   = 'noreply@andhika.com';
			$mail->Password   = 'PCWLzCWDQH8C'; 
			$mail->SMTPSecure = 'tls';
			$mail->Port       = 587;

			$mail->setFrom('noreply@andhika.com', 'Crewing System Notification');
			$mail->addAddress($recipientEmail);

			$mail->isHTML(true);
			$mail->Subject = 'Notifikasi Approve Crew Evaluation';
			$mail->Body = "
				<div style='font-family: Arial, sans-serif; font-size: 14px; color: #333;'>
					<p>Dear Bu Eva,</p>
					<p>Silakan lakukan pengecekan approve terhadap Crew Evaluation pada tautan berikut:</p>
					<p><a href='$link' style='color: #1a73e8;'>$link</a></p>
					
					<p>Terima kasih.</p>
					<br>
					<p><em>Email ini dikirim otomatis oleh Crewing System.</em></p>
				</div>
			";

			if (!$mail->send()) {
				log_message('error', 'Email failed: ' . $mail->ErrorInfo);
			} else {
				log_message('info', "Approval email sent to $recipientEmail");
			}
		} catch (Exception $e) {
			log_message('error', 'Exception sending email: ' . $e->getMessage());
		}
	}

	function rejectCrewEvaluation() {
		$data = $_POST;
		$txtIdReport = $data['txtIdReport'];
		$reasonReject = $data['txtReasonReject'];
		
		try {
			$updateData = array(
				'st_reject' => 'Y',
				'remark_reject' => $reasonReject
			);

			$this->MCrewscv->updateData(
				array('id' => $txtIdReport),
				$updateData,
				'crew_evaluation_report'
			);

			$response = array(
				'status' => 'success',
				'message' => 'Reject reason saved successfully.'
			);
		} catch (Exception $e) {
			$response = array(
				'status' => 'error',
				'message' => 'Error: ' . $e->getMessage()
			);
		}

		echo json_encode($response);
	}


	function approveCM() {
		$data = $_POST;
		$txtIdReport = $data['txtIdReport'];
		$txtDateReceipt = $data['txtDateReceipt'];
		try {
			$qrCodeFileName = $this->createQRCode($txtIdReport, 'approveCM');
			$updateData = array(
				'date_of_receipt' => $txtDateReceipt,
				'qrcode_reporting_cm' => $qrCodeFileName,
				'st_submit_cm' => 'Y'
			);
			
			$this->MCrewscv->updateData(
				array('id' => $txtIdReport),
				$updateData,
				'crew_evaluation_report'
			);
			
			$response = array(
				'status' => 'success',
				'message' => 'Approved Seafarer successfully!'
			);
		} catch (Exception $e) {
			$response = array(
				'status' => 'error',
				'message' => 'Error: ' . $e->getMessage()
			);
		}
		echo json_encode($response);
	}
}