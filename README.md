## Запуск контейнера

`sudo docker-composer up --build` \
Или \
`sudo docker-composer up --build -d` (В виде демона)

Если что то пойдет не так

`sudo docker-composer down` (Остановка контейнеров) \
`sudo docker-composer rm -fv` (Удаление контейнеров и предустановленных баз)

Нужно импортировать базу для начала работы

GET /api/users - All users

GET /api/user/1 \
POST /api/user/1 \
PUT /api/user/1 \
DELETE /api/user/1

API соответствует общепринятому стандарту

Не реализована нормальная обработка ошибок и логирование.