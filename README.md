# Cober

## Technical Test Cober 4 ITL Group

## Doc
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

## Developer notes

In order to make this POC, assumptions were made : 
- 1 CSV file is present per ZIP
- File charset is *always* **CP1252**
- CSV fields imported are limited to defined in `ImportData.php`

**What It can do :**

**1)** Download, extract and import a file from `http://files.data.gouv.fr/sirene/`
    
    through CLI : `php bin/console app:import-run 2018088`

**2)** Answer an HTTP/GET request to display company info or return a JSON answer containing `"no company were found"`.

**What it doesn't do / limitations** 
1) Import monthly file (~9 millions line CSV)
2) Check file consistency (errors, valid types etc.)
3) Remove duplicated data / data consolidation. Eg: A company already exists in DB, as no UUID provided in file
4) Check SIREN number is well-formed through a REGEX

## Runtime environment
Developed and tested on the given configuration : 
- PHP 7.3.5
- MySQL 5.7
- Windows 10

## Deploy 

```
composer install
php bin/console server:run
```                              

