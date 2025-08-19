# MCP Server Setup Guide

This guide provides installation commands for common MCP servers in both WSL/Bash and Windows PowerShell environments.

## Prerequisites

- Claude Code installed and configured
- Node.js and npm/npx installed
- For Git server: Python with `uv` package manager

## Server Installation Commands

### Filesystem Server

**WSL/Bash:**

```bash
# Local scope (default - current project only)
claude mcp add filesystem -- npx -y @modelcontextprotocol/server-filesystem "."

# Project scope (shared with team via .mcp.json)
claude mcp add filesystem -s project -- npx -y @modelcontextprotocol/server-filesystem "."

# User scope (available across all your projects)
claude mcp add filesystem -s user -- npx -y @modelcontextprotocol/server-filesystem "."
```

**Windows PowerShell:**

```powershell
# Local scope (default - current project only)
claude mcp add filesystem -- cmd /c -- npx -y @modelcontextprotocol/server-filesystem "."

# Project scope (shared with team via .mcp.json)
claude mcp add filesystem -s project -- cmd /c -- npx -y @modelcontextprotocol/server-filesystem "."

# User scope (available across all your projects)
claude mcp add filesystem -s user -- cmd /c -- npx -y @modelcontextprotocol/server-filesystem "."
```

### Git Server

**WSL/Bash:**

```bash
# Local scope
claude mcp add git -- uvx mcp-server-git

# Project scope
claude mcp add git -s project -- uvx mcp-server-git

# User scope
claude mcp add git -s user -- uvx mcp-server-git
```

**Windows PowerShell:**

```powershell
# Local scope
claude mcp add git -- cmd /c -- uvx mcp-server-git

# Project scope
claude mcp add git -s project -- cmd /c -- uvx mcp-server-git

# User scope
claude mcp add git -s user -- cmd /c -- uvx mcp-server-git
```

### Puppeteer Server

**WSL/Bash:**

```bash
# Local scope
claude mcp add puppeteer -- npx -y @modelcontextprotocol/server-puppeteer

# Project scope
claude mcp add puppeteer -s project -- npx -y @modelcontextprotocol/server-puppeteer

# User scope
claude mcp add puppeteer -s user -- npx -y @modelcontextprotocol/server-puppeteer
```

**Windows PowerShell:**

```powershell
# Local scope
claude mcp add puppeteer -- cmd /c -- npx -y @modelcontextprotocol/server-puppeteer

# Project scope
claude mcp add puppeteer -s project -- cmd /c -- npx -y @modelcontextprotocol/server-puppeteer

# User scope
claude mcp add puppeteer -s user -- cmd /c -- npx -y @modelcontextprotocol/server-puppeteer
```

### Sequential Thinking Server

**WSL/Bash:**

```bash
# Local scope
claude mcp add sequential-thinking -- npx -y @modelcontextprotocol/server-sequential-thinking

# Project scope
claude mcp add sequential-thinking -s project -- npx -y @modelcontextprotocol/server-sequential-thinking

# User scope
claude mcp add sequential-thinking -s user -- npx -y @modelcontextprotocol/server-sequential-thinking
```

**Windows PowerShell:**

```powershell
# Local scope
claude mcp add sequential-thinking -- cmd /c -- npx -y @modelcontextprotocol/server-sequential-thinking

# Project scope
claude mcp add sequential-thinking -s project -- cmd /c -- npx -y @modelcontextprotocol/server-sequential-thinking

# User scope
claude mcp add sequential-thinking -s user -- cmd /c -- npx -y @modelcontextprotocol/server-sequential-thinking
```


## Understanding Scopes

- **Local scope** (default): Only available in current project directory
- **Project scope**: Shared with team, creates `.mcp.json` file in project root
- **User scope**: Available across all your projects on your machine

## Management Commands

```bash
# List all configured servers
claude mcp list

# Get details for a specific server
claude mcp get server-name

# Remove a server
claude mcp remove server-name

# Check MCP server status within Claude Code
/mcp
```

## Key Differences

- **WSL/Bash**: Clean commands, no wrapper needed
- **Windows PowerShell**: Requires `cmd /c --` wrapper before `npx`/`uvx` commands
- **Filesystem server**: Includes `.` at the end to specify current directory
- **Git server**: Uses `uvx` instead of `npx` (different package manager)
- **Other servers**: Use `npx` with respective package names

## Troubleshooting

### Windows PowerShell Issues

- If `-y` flag causes errors, try `--yes` instead
- Always use `cmd /c --` wrapper for `npx` commands on native Windows
- For parameter parsing issues, try quoting the flag: `npx "-y"`

### General Issues

- Use `claude mcp list` to verify server installation
- Check server status with `/mcp` command in Claude Code
- Remove and re-add servers if experiencing connection issues

## Security Note

⚠️ **Warning**: Use third-party MCP servers at your own risk. Make sure you trust the MCP servers, especially those that connect to the internet, as they can introduce prompt injection risks.

## Additional Resources

- [MCP Documentation](https://modelcontextprotocol.io/introduction)
- [Claude Code Documentation](https://docs.anthropic.com/en/docs/claude-code)
- [MCP Servers Repository](https://github.com/modelcontextprotocol/servers)
