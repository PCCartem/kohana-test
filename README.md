## Запуск контейнера

`sudo docker-composer up --build` \
Или \
`sudo docker-composer up --build -d` (В виде демона)

Если что то пойдет не так

`sudo docker-composer down` (Остановка контейнеров) \
`sudo docker-composer rm -fv` (Удаление контейнеров и предустановленных баз)