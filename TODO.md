# TO DO List

1. `register auto [ --by-uuid | --by-name ]`  
    Automatic VM# configuration that sorts virtual machines by UUID or Name and
    writes the VM# list into the .VBoxCLI configuration file. Default is sort
    by name.

2. `register set <uuid> <vm#>`  
    Manual VM# configuration that stores user's settings into .VBoxCLI
    configuration file.

3.  No VM# warning  
    Warn when one or more virtual machines have no VM#. Suggest to run automatic or manual numbering. Error message when none of the machines are numbered.

4.  Add a README.md  
    Compose a README.md file for github once we are ready to export the project
    there.

5.  Create and enhance more commands:
    * `shutdown` (acpi button)
    * `poweroff`
    * `ls -r` (list running vms)
    * `info <VM#>` from the output of
```bash
VBoxManage showvminfo <uuid> --machinereadable
```
