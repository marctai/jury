<?php

class UsersModel extends Model
{
	public function insertUserData($name, $password)
	{
		// TODO: hasha lösenordet

		try
		{
			$stmt = $this->pdo->query('INSERT INTO users SET name = ' . $this->pdo->quote($name) . ', password = ' . $this->pdo->quote($password));
		}
		catch (PDOException $e)
		{
			// Check if name already exists
			if ($this->pdo->errorCode() == '23000')
			{
				errorHandler::sendError(errorHandler::ERRORCODE_403, '403', 'Name already exist.');
			}
			else
			{
				errorHandler::sendError(errorHandler::ERRORCODE_500, '500', 'What have you done!!');
			}
		}
		
		return (bool) ($stmt->rowCount() > 0);
	}

	public function login($name, $password)
	{
		try
		{
			$stmt = $this->pdo->query('SELECT name FROM users WHERE name = ' . $this->pdo->quote($name) . 'AND password = ' . $this->pdo->quote($password));
		}
		catch (PDOException $e)
		{
			errorHandler::sendError(errorHandler::ERRORCODE_500, '500', 'What have you done!!');
		}

		return $stmt->fetchColumn();
	}
}