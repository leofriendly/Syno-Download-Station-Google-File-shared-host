<?php

class googledrivedownload {
    private $Url;
    private $Username;
    private $Password;
    private $HostInfo;
	private $COOKIE_JAR = '/tmp/googledrive.cookie';
    private $LOG_FILE = '/tmp/googledrive.log';
    
    public function __construct($Url, $Username, $Password, $HostInfo) {
        if(strpos($Url,'http://') !== FALSE){
            $Url = str_replace("http://", "https://", $Url);
        }else{
            if(strpos($Url,'https://') === FALSE){
                $Url = "https://" . $Url;
            }
        }

        $this->Url = $Url;
        $this->Username = $Username;
        $this->Password = $Password;
        $this->HostInfo = $HostInfo;

        ini_set('max_execution_time', 300);
    }
	public function GetDownloadInfo() {
		$DownloadInfo = array();
		$this->logInfo("Start Get Link: " . $this->Url);
		$service_url = "https://drive.google.com/u/0/uc?id=";
		preg_match('/\/d\/(.+)\//', $this->Url, $result);
		if (isset($result[1]))
			$fileId = $result[1];
		else {
			preg_match('/id=([^&]+)/', $this->Url, $result);
			$fileId = $result[1];
		}
		if (isset($fileId)){
			$downloadUrl = $service_url . $fileId . "&confirm=t";
			$DownloadInfo[DOWNLOAD_URL] = trim($downloadUrl);
		}
		else {
			$DownloadInfo[DOWNLOAD_ERROR] = "Get link fail";
		}
		$this->logInfo("Url exchange redirected successfully: " .$DownloadInfo[DOWNLOAD_URL]);
		$this->logInfo("End getting download info");
        return $DownloadInfo;
    }
	private function logError($msg) {
        $this->log("[ERROR]", $msg);
    }

    private function logInfo($msg) {
        $this->log("[INFO]", $msg);
    }

    private function log($prefix, $msg) {
        error_log($prefix . " - " . date('Y-m-d H:i:s') . " - " . $msg . "\n", 3, $this->LOG_FILE);
    }
}
?>