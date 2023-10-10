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

## Improvements for next time

- Don't include passwords in the env file when adding it to git
- Expose API endpoints
- Batch assignment
- Throw exceptions for business rule violations
