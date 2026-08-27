<?php
namespace Vmwarephp\Extensions;

#[\AllowDynamicProperties]
class SessionManager extends \Vmwarephp\ManagedObject {

	private $session;

	function acquireSession($userName, $password) {
		if ($this->session) {
			return $this->session;
		}
		try {
			$this->session = $this->acquireSessionUsingCloneTicket();
		} catch (\Exception $e) {
			$this->session = $this->acquireANewSession($userName, $password);
		}
		return $this->session;
	}

	private function acquireSessionUsingCloneTicket() {
		$cloneTicket = $this->readCloneTicket();
		if (!$cloneTicket) {
			throw new \Exception('Cannot find any clone ticket.');
		}
		return $this->CloneSession(array('cloneTicket' => $cloneTicket));
	}

	private function acquireANewSession($userName, $password) {
		$session = $this->Login(array('userName' => $userName, 'password' => $password, 'locale' => null));
		$cloneTicket = $this->AcquireCloneTicket();
		$_SESSION['vmwarephp_session_ticket'] = $cloneTicket;
		return $session;
	}

	private function readCloneTicket() {
		if (!empty($_SESSION['vmwarephp_session_ticket'])) {
			return $_SESSION['vmwarephp_session_ticket'];
		}
		return false;
	}
}
