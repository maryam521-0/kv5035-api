# KV5035 Submission

## How to deploy on Nuwebspace
1. Upload entire `KV5035_Submission/` folder to your Nuwebspace: `~/public_html/KV5035/coursework/`.
2. Place `hri2023.sqlite` (provided in assessment resources) in `KV5035/coursework/database/`.
3. Update student details in `src/Controllers/AboutController.php`.
4. (Optional) Change expected API key in `config/bootstrap.php` (`$EXPECTED_KEY`).

## Test
- Base URL: `https://<your-id>.nuwebspace.co.uk/KV5035/coursework/api/`
- Try: `/about`, `/people`, `/research` using Postman.
- Import `postman/KV5035_API.postman_collection.json` into Postman.

## Security
- API key required via `x-api-key` or `?api_key`.
- Prepared statements for DB queries.

## Notes
- Do not modify DB schema per brief.
- OPTIONS supported automatically for preflight.