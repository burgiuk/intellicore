## Installation

To install this application you will need to ensure that you have [Docker](https://www.docker.com) available on your local machine.

1. Check out the repository from [GitHub](https://github.com/burgiuk/intellicore)
2. 

## How to Use
This is a command line application

### Check Available Allocation
To check the available number of unallocated codes run this command:
```shell
docker-compose exec app php artisan doorcode:unallocated
```
This will display the total number of door codes available to be allocated, similar to the output shown below:
```text
There are 979973 unallocated codes available.
```

### Assign a Specific Code 
If you want to assign a specific code to someone you can do by using the follow command:
```shell
docker-compose exec app php artisan doorcode:assign
```
You will be then asked to enter the 6 digit code you want to allocate. Enter this and press return.

Next you will be asked to enter the name of the person you would like the code to be associated with, again press return.

If the assignment was successful you will see a message like:
```text
The code has been successfully assigned to Joe Bloggs
```
If the assignment was unsuccessful you will see a message like the one below. You will need to check the details you entered and try again:
```text
Unable to assign that code. Please try another code.
```

## Improvements for next time

- Don't include passwords in the env file when adding it to git
- Expose API endpoints
- Batch assignment
- Throw exceptions for business rule violations
