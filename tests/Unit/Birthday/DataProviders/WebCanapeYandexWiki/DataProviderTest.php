<?php

namespace Tests\Unit\Birthday\DataProviders\WebCanapeYandexWiki;

use App\Libs\YandexSdk\Wiki\GetPage;
use App\Libs\YandexSdk\Wiki\YandexWiki;
use App\Services\Birthday\DataProviders\WebcanapeYandexWiki\DataProvider;
use App\Services\Birthday\UserData;
use PHPUnit\Framework\TestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;

class DataProviderTest extends TestCase
{
	public function test_can_get_users()
	{
		$mockClient = new MockClient([
			GetPage::class => function (PendingRequest $pendingRequest) {
				$slug = $pendingRequest->getRequest()->query()->get('slug');

				switch ($slug) {
					case 'spisok-i-kontaktnye-dannye-sotrudnikov/administracija':
						return MockResponse::make(body: json_encode([
							'content' => $this->getStaffDetails1()
						]));
					case 'spisok-i-kontaktnye-dannye-sotrudnikov/administracija2':
						return MockResponse::make(body: json_encode([
							'content' => $this->getStaffDetails2()
						]));
					case 'hr/kontaktnye-dannye-sertifikaty-i-t.d.-sotrudnikov/dni-rozhdenija-sotrudnikov':
						return MockResponse::make(body: json_encode([
							'content' => $this->getBirthdays()
						]));
				}
			}
		]);

		$wiki = new YandexWiki('test', 1);
		$wiki->withMockClient($mockClient);

		$provider = new DataProvider(
			config: $this->getProviderConfig()
		);
		$provider->setYandexWikiClient($wiki);

		$users = $provider->getUsers();

		assertCount(2, $users);

		[$ivanov, $petrov] = $users;

		assertInstanceOf(UserData::class, $ivanov);
		assertInstanceOf(UserData::class, $petrov);

		assertSame([
			'first_name' => 'Иван',
			'last_name' => 'Иванов',
			'birthdate' => '2025-01-05',
			'photo' => 'https://wiki.yandex.ru/storage/ivanov.png',
			'post' => 'Директор'
		], $ivanov->toArray());

		assertSame([
			'first_name' => 'Василий',
			'last_name' => 'Петров',
			'birthdate' => '2025-09-30',
			'photo' => 'https://wiki.yandex.ru/storage/petrov.png',
			'post' => 'Дизайнер'
		], $petrov->toArray());
	}

	private function getProviderConfig()
	{
		return [
			'birthdates_page_slug' => 'hr/kontaktnye-dannye-sertifikaty-i-t.d.-sotrudnikov/dni-rozhdenija-sotrudnikov',
			'staff_detail_pages' => [
				'spisok-i-kontaktnye-dannye-sotrudnikov/administracija',
				'spisok-i-kontaktnye-dannye-sotrudnikov/administracija2',
			],
			'yandex_oauth_token' => 'test',
			'yandex_org_id' => 1
		];
	}

	private function getStaffDetails1()
	{
		return "#|\n||\n\n**ФИО**\n\n|\n\n**Фото**\n\n|\n\n**Должность**\n\n|\n\n**Внутренний**\n\n|\n\n**Контакты**\n\n|\n\n**Немного о себе**\n\n||\n||\n\nИван Иванов\n\n|\n\n![Иванов (Директор).png](/storage/ivanov.png =349x)\n\n|\n\nДиректор\n\n|\n\n402\n\n|\n\n8 (999) 999-99-99\n\n[ivanov@example.ru](mailto:ivanov@example.ru)\n\n|\n\nЗначимость этих проблем настолько очевидна, что начало повседневной работы по формированию позиции требует от нас системного анализа модели развития!\n\nЗадача...\n\n||\n|#";
	}

	private function getStaffDetails2()
	{
		return "#|\n||\n\n**ФИО**\n\n|\n\n**Фото**\n\n|\n\n**Должность**\n\n|\n\n**Внутренний**\n\n|\n\n**Контакты**\n\n|\n\n**Немного о себе**\n\n||\n||\nВасилий Петров\n\n|\n\n![Петров (Дизайнер).png](/storage/petrov.png =349x)\n\n|\n\nДизайнер\n\n|\n\n401\n\n|\n\n8 (988) 888-88-88\n\n[petrov@example.ru](mailto:petrov@example.ru)\n\n|\n\nЗначимость этих проблем настолько очевидна, что начало повседневной работы по формированию позиции требует от нас системного анализа модели развития!\n\nЗадача...\n\n||\n|#";
	}

	private function getBirthdays()
	{
		return "#|\n||\n\n**Дата**\n\n|\n\n**ФИО**\n\n||\n||\n\n05 января\n\n|\n\nИван Иванов\n\n||\n||\n\n30 сентября\n\n|\n\nВасилий Петров\n\n||\n|#\n\n&nbsp;\n";
	}
}
