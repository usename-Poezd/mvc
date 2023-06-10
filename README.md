# My MVC pattern with NO LIBS

## Project struct
 ~~~
root/
├── app/ 
│   ├── Contracts/   
│   └── Routing/
├── docker/
│   └── nginx/
│       └── conf.d/
├── public/
├── routes/
├── src/ 
    └── Controllers/ 
 ~~~   

### app/
Project core. It loads the service settings, initializes the framework, and searches for the required controller.
### docker/
Folder for configs of docker containers
### public/
The entry point to the project.
### routes/
Routes folder, which stores descriptions of all routes of service
### src/
Framework extension point. This is where developers put their code. Implemented based on the MVC pattern. Controller - contains controllers. The entry point of all requests to the server and the subsequent formation of the response. Model - contains models. Gets data from various sources (DB, external api, etc.). Service - business logic layer. Contains code that is most likely to be refactored and modified. View - contains a view. Generates json server response.