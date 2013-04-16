<?php
class DatabaseHandler
{
	private $connection;
	
	// Opent een verbinding met de database via een PDO protocol
	public function openConnection($host, $user, $pass)
	{
		try
		{
			$this->connection = new PDO($host, $user, $pass);
		}
		catch(Exception $e)
		{
			return false;
		}
		return true;
	}
	
	// Alle queries die worden uitgevoerd worden hier meegegeven met de bijhorende parameters en verstuurt naar de database. 
	public function executeQuery($query, $param=array())
	{
		//zet de query klaar op de database voor executie.
		$st = $this->connection->prepare($query);
		
		//als er geen errors optraden bij het preparen, execute de query.
		if($st->errorCode() == '')
			$st->execute($param);
		else
			return 'Error: query could not be executed.';
		
		// Stuurt de gegevens terug naar waar ze zijn opgevraagd.
		return $st;
	}
}