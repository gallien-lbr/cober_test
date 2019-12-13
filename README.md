# Cober

## Test technique cober ITL Group
```shell script
# fetch info about company
http://localhost:8000/api/findBySiren/[SIREN]

# import daily data cmd 
php bin/console app:import-run [YYYYDDM]

# import monthly data cmd
php bin/console app:import-monthly
```

## Runtime environment
- PHP 7.3.5
- MySQL = 5.7