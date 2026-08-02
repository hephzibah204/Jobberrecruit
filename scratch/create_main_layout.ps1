(Get-Content 'app\Views\admin\layouts\app.php') -replace "renderSection\('section'\)", "renderSection('content')" | Set-Content 'app\Views\admin\layouts\main.php' -Encoding UTF8
Write-Output "Done"
