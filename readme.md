## Installation

To install this application you will need to ensure that you have 
[Docker](https://www.docker.com) available on your local machine.

1. Check out the repository from [GitHub](https://github.com/burgiuk/intellicore)
2. Navigate to the repository directory in your terminal.
3. Run the command `docker build --no-cache .` to create the necessary virtual machine images.
4. Next run the command `docker-compose up -d` to start the Docker VMs.
5. When they have successfully started running, run the command 
`docker-compose exec app composer install`.
This will install Laravel for you.
6. After Laravel is installed you will need to prepare the database for the first run. 
To do this run the command `docker-compose exec app php artisan migrate`
7. You are now ready to run the application commands as you wish.

## How to Use
This is a command line application

### Check Available Allocation
To check the available number of unallocated codes run this command:
```shell
docker-compose exec app php artisan doorcode:unallocated
```
This will display the total number of door codes available to be allocated, similar to the 
output shown below:
```text
There are 979973 unallocated codes available.
```
### Generate and Assign a Random Code
To generate and assign a random code to a person the following code should be run:
```shell
docker-compose exec app php artisan doorcode:generate
```
Next you will be asked to enter the name of the person you would like the code to be 
associated with, press return.

If the assignment was successful you will see a message like:
```text
The code "194846" has been assigned to sam
```
### Assign a Specific Code 
If you want to assign a specific code to someone you can do by using the follow command:
```shell
docker-compose exec app php artisan doorcode:assign
```
You will be then asked to enter the 6 digit code you want to allocate. Enter this and press 
return.

Next you will be asked to enter the name of the person you would like the code to be 
associated with, again press return.

If the assignment was successful you will see a message like:
```text
The code has been successfully assigned to Joe Bloggs
```
If the assignment was unsuccessful you will see a message like the one below. You will need 
to check the details you entered and try again:
```text
Unable to assign that code. Please try another code.
```

## Improvements for next time

- Don't include passwords in the env file when adding it to git
- Expose API endpoints, this would allow external applications to generate and assign 
doorcodes. Additionally, you might want to provide a GGUI for people to use. This could also
take advantage of the API endpoints. 
- Batch assignment, this would be useful for the initial population of employee codes. It 
would be labourious to generate lots of codes manually 
- Throw exceptions for business rule violations, this will give cleaner and more informative
error messages to the user.
- Include unit tests, these have not been included due to time constraints as well as not 
being exposed to much TDD during my career. It is something I am working to remedy with 
online courses from LinkedIn Learning.
