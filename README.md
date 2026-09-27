# Engagifii Plugin for WordPress

[![GitHub release](https://img.shields.io/github/v/release/thakurgit/engagifii?color=blue&label=version)](https://github.com/thakurgit/engagifii/releases/latest)
[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0.html)

The **Engagifii Module** is an official WordPress plugin that integrates directly with the Engagifii platform API to seamlessly display legislation bills, public official profiles, training courses, classes, events, and organization directories on your WordPress website.

---

## 🌟 Key Features

- **Bill & Legislation Tracking**: Display real-time legislative bills, tracking levels, status actions, and bill details.
- **Public Officials Directory**: Showcase elected public officials with detailed profile views.
- **Events & Training Calendar**: Integrated interactive calendar and grid views for upcoming events and training classes.
- **Courses & Classes**: Complete directory listing for educational courses and scheduled classes.
- **Organization Directory**: Comprehensive organization profiles with exhibitor overview tabs, contact info, and dynamic layout cards.
- **Automated Updates**: Built-in update notification system using GitHub Releases for seamless single-click plugin updates.

---

## 🚀 Quick Installation

1. Download the latest release package:
   👉 **[Download engagifii.zip](https://github.com/thakurgit/engagifii/releases/latest/download/engagifii.zip)**
2. Go to your WordPress Admin Dashboard -> **Plugins** -> **Add New** -> **Upload Plugin**.
3. Choose `engagifii.zip` and click **Install Now**.
4. Click **Activate Plugin**.
5. Navigate to **Engagifii Settings** in your WordPress admin menu to configure your Tenant Code and API credentials.

---

## 📌 Available Shortcodes

You can easily embed Engagifii modules into any page or post using the following shortcodes:

| Module | Shortcode | Description |
| :--- | :--- | :--- |
| **Bills List** | `[legislation-list]` | Displays the full legislative bills listing. |
| **Bill Detail** | `[legislation-details Id='bill-id']` | Displays details for a specific bill. |
| **Public Officials** | `[public-officials]` | Displays the public officials directory. |
| **Official Detail** | `[public-officials-detail]` | Displays details for a specific official. |
| **Classes List** | `[classes-list-calendar-class-name calendarclassname=true]` | Displays scheduled classes with calendar view. |
| **Class Details** | `[class-details Id='class-id']` | Displays details for a specific class. |
| **Courses List** | `[courses-list]` | Displays the available courses directory. |
| **Course Details** | `[course-details Id='course-id']` | Displays details for a specific course. |
| **Events List** | `[events-list-calendar calendar=true]` | Displays scheduled events with calendar view. |
| **Event Details** | `[events-details Id='event-id']` | Displays details for a specific event. |
| **Training Calendar** | `[training-calendar]` | Displays a combined calendar for events & classes. |
| **Organization Details** | `[organization-details Id='organization-id']` | Displays organization profile details. |

---

## 🛠️ System Requirements

- **WordPress**: 6.0 or higher
- **PHP**: 7.4 or higher
- **License**: GPLv3 or later

---

## 📄 License

Distributed under the GNU General Public License v3.0 (or later). See `license.txt` for more information.
