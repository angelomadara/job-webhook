# Job Tracker

This is a job tracker app that receives jobs from a third-party app, and sends jobs to google API

### Installation

See Laravel documentation for installation

### JWT-auth
https://jwt-auth.readthedocs.io/en/develop/auth-guard/

POST `api/v1/login` - login with {email: '', password: ''}

- this will return `access_token` that can be used to make transactions

##### These are the endpoints for the job resource
```PHP
GET|HEAD api/v1/job
```
```PHP
GET|HEAD api/v1/job/{job_id}
```
```PHP
POST api/v1/job
```
```PHP
PUT|PATCH api/v1/job/{job_id}
```
```PHP
DELETE api/v1/job/{job_id}
```
