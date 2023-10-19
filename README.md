### RUN

- Clone repository
  > $ git clone git@github.com:Dofu1130/you-wo.git

- Copy `.env.example` to `.env` and modify according to your environment
  >$ cp .env.example .env

- Start environment
  > $ docker-compose up -d

- Adjust executable file permissions
  > $ docker-compose exec web chmod 755 dockerfiles/web/bin/preparation.sh

- Build project
  > $ docker-compose exec web ./dockerfiles/web/bin/preparation.sh
  
- Api document:
  https://documenter.getpostman.com/view/11650144/2s9YXh6hu5

