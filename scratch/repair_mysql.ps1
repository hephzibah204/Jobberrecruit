$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$oldName = "data_old_$timestamp"

Write-Output "Renaming C:\xampp\mysql\data to C:\xampp\mysql\$oldName..."
Rename-Item -Path "C:\xampp\mysql\data" -NewName $oldName

Write-Output "Creating new empty C:\xampp\mysql\data..."
New-Item -ItemType Directory -Path "C:\xampp\mysql\data"

Write-Output "Copying backup templates to C:\xampp\mysql\data..."
Copy-Item -Path "C:\xampp\mysql\backup\*" -Destination "C:\xampp\mysql\data" -Recurse -Force

if (Test-Path "C:\xampp\mysql\$oldName\jobberrecruit") {
    Write-Output "Copying jobberrecruit database folder..."
    Copy-Item -Path "C:\xampp\mysql\$oldName\jobberrecruit" -Destination "C:\xampp\mysql\data" -Recurse -Force
}

Write-Output "Copying ibdata1..."
Copy-Item -Path "C:\xampp\mysql\$oldName\ibdata1" -Destination "C:\xampp\mysql\data" -Force

Write-Output "Repair completed successfully!"
