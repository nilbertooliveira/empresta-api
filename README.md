## Ambiente Docker

Foi utlizado a arquitetura abaixo para concepção do projeto.

Services, Repository, RestFull e Docker.

1. Clonar o repositório:
`git clone https://github.com/nilbertooliveira/backend-careers.git`

2. Acessar a pasta do projeto "docker" e rodar os próximos comandos:
`docker-compose up -d`

3. Instalar as dependências:
 ```
docker-compose exec phpfpm composer install
docker-compose exec phpfpm php artisan key:generate

4. Permissoes de pastas
`sudo chmod -R 777 storage/ bootstrap/`

5. Executar testes
`docker-compose exec phpfpm php vendor/bin/phpunit`

## Utilização das APIS

[Documentação Postman](https://documenter.getpostman.com/view/10569259/TWDcGadV)
