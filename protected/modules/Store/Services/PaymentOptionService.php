<?php

namespace Store\Services;

use DScribe\Core\AService;
use Store\Models\PaymentOption;

class PaymentOptionService extends AService {

	protected $form;

	/**
	 * Inject form into the service
	 * @return array
	 */
	protected function inject() {
		return array(
			'form' => array(
				'class' => 'Store\Forms\PaymentOptionForm'
			),
		);
	}

	/**
	 * Allow public access to form
	 * @return \Store\Forms\PaymentOptionForm
	 */
	public function getForm() {
		return $this->form;
	}

	/**
	 * Fetch all data in the database
	 * return array
	 */
	public function fetchAll() {
		return $this->repository->fetchAll();
	}

	/**
	 * Find a row from database
	 * @param mixed $id Id to fetch with
	 * @return mixed
	 */
	public function findOne($id) {
		$this->model = $this->repository->findOne($id);
		return $this->model;
	}

	/**
	 * Inserts data into the database
	 * @param \Store\Models\PaymentOption
	 * return boolean
	 */
	public function create(PaymentOption $model) {
		$this->repository->insert($model)->execute();
		return $this->flush();
	}

	/**
	 * Saves data into the database
	 * @param \Store\Models\PaymentOption
	 * return boolean
	 */
	public function save(PaymentOption $model) {
		$this->repository->update($model, 'id')->execute();
		return $this->flush();
	}

	/**
	 * Deletes data from the database
	 * return boolean
	 */
	public function delete() {
		$this->repository->delete($this->model)->execute();
		return $this->flush();
	}

}
		