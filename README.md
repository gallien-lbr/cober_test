# Cober

## Technical Test Cober 4 ITL Group
```shell script
# fetch info about company
http://localhost:8000/api/findBySiren/[SIREN]

# import daily data cmd 
php bin/console app:import-run [YYYYDDM]

# import monthly data cmd (TODO)
php bin/console app:import-monthly
```
## About

To see how this was done, check files below:
- `src/Command/ImportData.php`
- `src/Controller/ApiController.php`


## Runtime environment
Developed and tested on the given configuration : 
- PHP 7.3.5
- MySQL 5.7