# KV5035 Coursework API – Documentation

**Module:** KV5035 Software Architecture  
**Base URL (example):** `https://w123456.nuwebspace.co.uk/KV5035/coursework/api/`

> Replace `w123456` with your Nuwebspace ID.

## Auth
Supply your key as header `x-api-key: <YOUR_KEY>` or `?api_key=<YOUR_KEY>`.

---

## Endpoints

### GET /about
**Response**
```json
{"student_id":"w12345678","degree_programme":"BSc Computer Science","full_name":"Your Name","module_code":"KV5035"}
```

### /people
- **GET** params: `person_id`, `research_id`, `search`, `page` (10 per page)
- **POST** body (json): `{"name":"New Author"}`
- **PATCH** body (json): `{"person_id":1,"name":"Updated Name"}`
- **DELETE** body (json): `{"person_id":1}`

**GET Response (array of objects)**
```json
[{"person_id":1,"name":"Alice"}, ...]
```

### /research
- **GET** params: `research_id`, `person_id`, `search`, `page`
**GET Response (array of objects)**
```json
[{"research_id":1,"title":"...","abstract":"...","type":"paper","award":""}]
```

- **POST** for Task 6:
  - Give/remove award:
    ```json
    {"research_id": 1, "award_id": 2, "action": "give"}
    ```
    or
    ```json
    {"research_id": 1, "award_id": 2, "action": "remove"}
    ```
  - Change type:
    ```json
    {"research_id": 1, "type_id": 3}
    ```

**Status codes:** 200, 201 (created), 204 (options), 400 (bad request), 401 (unauthorized), 404 (not found), 405 (method not allowed), 500 (error).

---

## Notes for Marking
- Object-Oriented, Front Controller, Autoloader, Exception Handler implemented.
- Clean URLs via `.htaccess`.
- SQLite used (`database/hri2023.sqlite`). **Ensure you place the provided DB file here.**
- No third-party libs.
- Robust JSON responses + appropriate status codes.
- Parameters not case sensitive (normalized).

---
Updated for student: Zafer Ahmad (w23042229)
API Key: w23042229-key
Base URL: https://w23042229.nuwebspace.co.uk/KV5035/coursework/api
