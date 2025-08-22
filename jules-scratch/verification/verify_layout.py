import asyncio
from playwright.async_api import async_playwright, expect

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page()

        try:
            # Go to login page
            await page.goto("http://127.0.0.1:8000/login")

            # Fill in credentials and login
            await page.get_by_label("Email").fill("admin@dentalclinic.com")
            await page.get_by_label("Password").fill("password")
            await page.get_by_role("button", name="Log in").click()

            # Wait for dashboard to load
            await expect(page).to_have_url("http://127.0.0.1:8000/dashboard")
            await expect(page.get_by_role("heading", name="Dashboard")).to_be_visible()

            # Take screenshot of expanded sidebar
            await page.screenshot(path="jules-scratch/verification/expanded_sidebar.png")

            # Find and click the toggle button
            toggle_button = page.get_by_role("button").first
            await toggle_button.click()

            # Wait for sidebar to collapse (by checking for a class or style change)
            # A simple wait should be enough for the transition to complete
            await page.wait_for_timeout(500)

            # Take screenshot of collapsed sidebar
            await page.screenshot(path="jules-scratch/verification/collapsed_sidebar.png")

        except Exception as e:
            print(f"An error occurred: {e}")
            # Take a screenshot on error to help debug
            await page.screenshot(path="jules-scratch/verification/error.png")
        finally:
            await browser.close()

asyncio.run(main())
