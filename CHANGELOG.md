# Change Log

## 0.3.5 - 2016-09-12

### Added

- `Mekras\OData\Client\Exception\NetworkException`

### Changed

- `Service::sendRequest` now throws `Mekras\OData\Client\Exception\NetworkException` in case of
  network errors.


## 0.3.4 - 2016-09-01

### Changed

- Change MaxDataServiceVersion to 3.0 in requests.
- Change DataServiceVersion to 2.0 in requests.
- Made `$type` argument optional in `Mekras\OData\Client::createEntityDocument()`.